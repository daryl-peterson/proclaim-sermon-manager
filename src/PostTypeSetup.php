<?php

namespace DRPSermonManager;

use DRPSermonManager\Exceptions\PluginException;
use DRPSermonManager\Interfaces\PostTypeRegInt;
use DRPSermonManager\Interfaces\PostTypeSetupInt;
use DRPSermonManager\Interfaces\TaxonomyRegInt;

/**
 * Register post types and taxonomies.
 * - Stub - calls other object methods.
 * - Used so other object don't have to register callbacks.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class PostTypeSetup implements PostTypeSetupInt
{
    private array $taxonomies;
    private array $postypes;

    protected function __construct()
    {
        $pt = Constant::POST_TYPE_SERMON;
        $this->postypes[$pt] = PostTypeSermonReg::init();
        $this->taxonomies[$pt][] = TaxPreacherReg::init();
        $this->taxonomies[$pt][] = TaxSeriesReg::init();
        $this->taxonomies[$pt][] = TaxTopicsReg::init();
        $this->taxonomies[$pt][] = TaxBibleBookReg::init();
        $this->taxonomies[$pt][] = TaxServiceTypeReg::init();
    }

    public static function init(): PostTypeSetupInt
    {
        return new self();
    }

    public function register(): void
    {
        add_action('init', [$this, 'add']);
        add_action(Constant::ACTION_FLUSH_REWRITE_RULES, [$this, 'flush']);
    }

    public function add(): void
    {
        try {
            $list = $this->getPostTypeList();

            foreach ($list as $postType) {
                /**
                 * @var PostTypeRegInt $objPostType
                 */
                $objPostType = $this->getPostType($postType);
                $objPostType->add();
                $taxonomies = $this->getPostTypeTaxonomies($postType);

                if (!isset($taxonomies)) {
                    // @codeCoverageIgnoreStart
                    continue;
                    // @codeCoverageIgnoreEnd
                }

                /**
                 * @var TaxonomyRegInt $taxonomy
                 */
                foreach ($taxonomies as $taxonomy) {
                    $taxonomy->add();
                }
            }

            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            FatalError::set($th);
            // @codeCoverageIgnoreEnd
        }
    }

    public function remove(): void
    {
        try {
            $list = $this->getPostTypeList();

            foreach ($list as $postType) {
                /**
                 * @var PostTypeRegInt $objPostType
                 */
                $objPostType = $this->getPostType($postType);
                $taxonomies = $this->getPostTypeTaxonomies($postType);

                if (!isset($taxonomies)) {
                    // @codeCoverageIgnoreStart
                    $objPostType->remove();
                    continue;
                    // @codeCoverageIgnoreEnd
                }

                /**
                 * @var TaxonomyRegInt $taxonomy
                 */
                foreach ($taxonomies as $taxonomy) {
                    $taxonomy->remove();
                }

                $objPostType->remove();
            }

            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            FatalError::set($th);
            // @codeCoverageIgnoreEnd
        }
    }

    public function flush(): void
    {
        flush_rewrite_rules();
    }

    public function getPostTypeList(): array
    {
        return array_keys($this->postypes);
    }

    public function getPostType(string $post_type): PostTypeRegInt
    {
        if (!isset($this->postypes[$post_type])) {
            throw new PluginException("Invalid post type : $post_type");
        }

        return $this->postypes[$post_type];
    }

    public function getPostTypeTaxonomies(string $post_type): ?array
    {
        if (!isset($this->taxonomies[$post_type])) {
            return null;
        }

        return $this->taxonomies[$post_type];
    }
}
