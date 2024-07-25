<?php

namespace DRPSermonManager\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Base test case.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class BaseTest extends TestCase
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        if (!defined('PHPUNIT_TESTING')) {
            define('PHPUNIT_TESTING', true);
        }
    }
}
