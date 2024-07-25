<?php

namespace DRPSermonManager\Core\Exceptions;

use DRPSermonManager\App;
use DRPSermonManager\Logger;

use const DRPSermonManager\NAME;

/**
 * Vimeo exception.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class VimeoException extends \Exception
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        /*

        $title = NAME.' Error';
        $msg = "Fatal Error Occured<br>$message";

        Logger::error(['MESSAGE' => $message, 'TRACE' => debug_backtrace(0, 5)]);

        $notice = App::getNoticeInt();
        $notice->setError($title, $msg);
        */
    }
}
