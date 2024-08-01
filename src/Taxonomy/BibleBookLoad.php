<?php

namespace DRPSermonManager\Taxonomy;

use DRPSermonManager\App;
use DRPSermonManager\Constant;
use DRPSermonManager\Interfaces\Initable;
use DRPSermonManager\Interfaces\Registrable;
use DRPSermonManager\Logging\Logger;

defined('ABSPATH') or exit;

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
class BibleBookLoad implements Initable, Registrable
{
    public static function init(): BibleBookLoad
    {
        return new self();
    }

    public function register(): void
    {
        add_action('drpsermon_after_post_setup', [$this, 'run'], 10, 1);
    }

    public function run(): void
    {
        $opts = App::getOptionsInt();

        $ran = $opts->get('bible_books_loaded', false);
        if ($ran && !defined('PHPUNIT_TESTING')) {
            // @codeCoverageIgnoreStart
            return;
            // @codeCoverageIgnoreEnd
        }

        $this->load();
        $opts->set('bible_books_loaded', true);
    }

    private function load()
    {
        $books = Constant::BIBLE_BOOKS;
        $tax = Constant::TAX_BIBLE_BOOK;

        try {
            foreach ($books as $book) {
                $slug = trim(strtolower(str_replace([' ', '_'], ['-', '-'], $book)));
                wp_insert_term($book, $tax, ['slug' => $slug]);
            }
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);
            // @codeCoverageIgnoreEnd
        }
    }
}
