<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DRPSermonManager\Interfaces;

/**
 * @author daryl
 */
interface NoticeInterface
{
    /**
     * Display notice if it exist.
     *
     * @since 1.0.0
     *
     * @return string|null
     */
    public function showNotice();

    /**
     * Set error notice.
     *
     * @since 1.0.0
     *
     * @param string $title   Title of notice
     * @param string $message Message context
     */
    public function setError(string $title, string $message): bool;

    /**
     * Set warning notice.
     *
     * @since 1.0.0
     *
     * @param string $title   Title of notice
     * @param string $message Message context
     */
    public function setWarning(string $title, string $message): bool;

    /**
     * Set info notice.
     *
     * @since 1.0.0
     *
     * @param string $title   Title of notice
     * @param string $message Message context
     */
    public function setInfo(string $title, string $message): bool;

    /**
     * Set success notice.
     *
     * @since 1.0.0
     *
     * @param string $title   Title of notice
     * @param string $message Message context
     */
    public function setSuccess(string $title, string $message): bool;

    /**
     * Delete admin notice.
     */
    public function delete(): void;
}
