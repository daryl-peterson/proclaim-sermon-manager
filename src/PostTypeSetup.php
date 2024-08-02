<?php

namespace DRPSermonManager;

use DRPSermonManager\Abstracts\PostTypeSetupAbs;
use DRPSermonManager\Constants\ACTIONS;
use DRPSermonManager\Constants\PT;
use DRPSermonManager\Interfaces\PostTypeRegInt;
use DRPSermonManager\Interfaces\PostTypeSetupInt;
use DRPSermonManager\Interfaces\TaxonomyRegInt;
use DRPSermonManager\Logging\Logger;
use DRPSermonManager\PostType\SermonReg;
use DRPSermonManager\Taxonomy\BibleBookReg;
use DRPSermonManager\Taxonomy\PreacherReg;
use DRPSermonManager\Taxonomy\SeriesReg;
use DRPSermonManager\Taxonomy\ServiceTypeReg;
use DRPSermonManager\Taxonomy\TopicsReg;

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
class PostTypeSetup extends PostTypeSetupAbs implements PostTypeSetupInt
{
    protected function __construct()
    {
        $pt = PT::SERMON;
        $this->postypes[$pt] = SermonReg::init();
        $this->taxonomies[$pt][] = PreacherReg::init();
        $this->taxonomies[$pt][] = SeriesReg::init();
        $this->taxonomies[$pt][] = TopicsReg::init();
        $this->taxonomies[$pt][] = BibleBookReg::init();
        $this->taxonomies[$pt][] = ServiceTypeReg::init();
    }

    public function register(): void
    {
        add_action('init', [$this, 'add']);
        add_action(ACTIONS::FLUSH_REWRITE_RULES, [$this, 'flush']);
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

            do_action('drpsermon_after_post_setup');

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
                    Logger::debug(['TAXONOMY' => $taxonomy]);
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
}
