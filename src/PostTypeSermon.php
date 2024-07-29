<?php

namespace DRPSermonManager;

use DRPSermonManager\Interfaces\Initable;
use DRPSermonManager\Interfaces\Registrable;

/**
 * Class description.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class PostTypeSermon implements Initable, Registrable
{
    protected string $postType;

    protected function __construct()
    {
        $this->postType = Constant::POST_TYPE_SERMON;
    }

    public static function init(): PostTypeSermon
    {
        return new self();
    }

    public function register(): void
    {
        add_action('pre_get_posts', [$this, 'fixOrdering'], 90);
    }

    public function fixOrdering(\WP_Query $query): void
    {
        $pt = $this->postType;
        if (is_admin() || !$query->is_main_query()) {
            return;
        }

        $taxonomies = TaxUtils::getTaxonomies($pt);
        if (!is_post_type_archive($pt) && !is_tax($taxonomies)) {
            return;
        }

        $opts = App::getOptionsInt();
        $orderby = $opts->get('archive_orderby', '');
        $order = $opts->get('archive_order', '');

        switch ($orderby) {
            case 'date_preached':
                $query->set('meta_key', 'sermon_date');
                $query->set('meta_value_num', time());
                $query->set('meta_compare', '<=');
                $query->set('orderby', 'meta_value_num');
                break;
            case 'date_published':
                $query->set('orderby', 'date');
                break;
            case 'title':
            case 'random':
            case 'id':
                $query->set('orderby', $orderby);
                break;
        }

        $query->set('order', strtoupper($order));

        $query->set('posts_per_page', $opts->get('sermon_count', get_option('posts_per_page')));
    }
}
