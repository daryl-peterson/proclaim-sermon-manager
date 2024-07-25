<?php

namespace DRPSermonManager;

use DRPSermonManager\Core\Interfaces\NoticeInterface;
use DRPSermonManager\Core\Interfaces\OptionsInterface;
use DRPSermonManager\Core\Traits\SingletonTrait;

/**
 * Admin notices.
 *
 * @author Daryl Peterson
 */
class Notice implements NoticeInterface
{
    use SingletonTrait;

    private OptionsInterface $options;
    private string $optName = 'notice';

    protected function __construct()
    {
        // @codeCoverageIgnoreStart
        $this->options = App::getOptionsInt();
        // @codeCoverageIgnoreEnd
    }

    /**
     * Display notice if it exist.
     */
    public function showNotice(): void
    {
        $option = $this->options->get($this->optName, null);
        Logger::debug(['OPTION NAME' => $this->options, 'OPTION VALUE' => $option]);
        if (!isset($option)) {
            return;
        }

        $title = isset($option['title']) ? $option['title'] : '';
        $message = isset($option['message']) ? $option['message'] : false;
        $noticeLevel = !empty($option['notice-level']) ? $option['notice-level'] : 'notice-error';
        if ($message) {
            $html = <<<HTML
            <div class='notice $noticeLevel is-dismissible'>
            <h2>$title</h2>
            <p>$message</p>
            </div>

HTML;
            echo $html;
            $this->options->delete($this->optName);
        }
    }

    public function delete(): void
    {
        $this->options->delete($this->optName);
    }

    public function setError($title, $message): bool
    {
        return $this->setOption($title, $message, 'notice-error');
    }

    public function setWarning(string $title, string $message): bool
    {
        return $this->setOption($title, $message, 'notice-warning');
    }

    public function setInfo(string $title, string $message): bool
    {
        return $this->setOption($title, $message, 'notice-info');
    }

    public function setSuccess(string $title, string $message): bool
    {
        return $this->setOption($title, $message, 'notice-success');
    }

    /**
     * Set option in database.
     *
     * @param type $noticeLevel
     */
    protected function setOption(string $title, string $message, string $noticeLevel): bool
    {
        $title = NAME." $title";
        $optValue = [
            'title' => $title,
            'message' => $message,
            'notice-level' => $noticeLevel,
        ];

        return $this->options->set($this->optName, $optValue);
    }
}
