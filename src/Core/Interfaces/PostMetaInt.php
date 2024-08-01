<?php

namespace DRPSermonManager\Interfaces;

/**
 * Post meta interface.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface PostMetaInt
{
    /**
     * Initialize object.
     *
     * @since 1.0.0
     */
    public static function init(): PostMetaInt;

    /**
     * Register hooks.
     *
     * @since 1.0.0
     */
    public function register(): void;

    /**
     * Save post meta.
     *
     * - Called\
     * apply_filter($this->name.'_meta_set', mixed $value);
     * >
     * - Use\
     * add_filter($this->name.'_meta_set', [$this, 'callback_name'], 10, 1);
     *
     * @see https://developer.wordpress.org/reference/functions/apply_filters/
     * @since 1.0.0
     */
    public function set(int $post_id): bool;

    /**
     * Get post meta.
     *
     * - Called\
     * apply_filter($this->name.'_meta_get', mixed $value);
     * >
     * - Use\
     * add_filter{$this->name.'_meta_get',[$this, 'callback_name'], 10, 1};
     *
     * @see https://developer.wordpress.org/reference/functions/apply_filters/
     * @since 1.0.0
     */
    public function get(int $post_id): mixed;

    /**
     * Get array of attachments.
     * - Returns null | array of Attachements.
     *
     * @since 1.0.0
     */
    public function getAttachments(int $post_id): ?array;

    /**
     * Get name for meta.
     *
     * @since 1.0.0
     */
    public function getName(): string;

    /**
     * Get label for post meta.
     *
     * @since 1.0.0
     */
    public function getLabel(): string;

    /**
     * Get taxonomy terms.
     *
     * @since 1.0.0
     */
    public function getTerms(): ?array;

    /**
     * Check if taxonomy exist for meta.
     *
     * @since 1.0.0
     */
    public function hasTaxonomy(): bool;

    /**
     * Get the taxonomy name for meta.
     *
     * @since 1.0.0
     */
    public function getTaxonomy(): ?string;

    public function getDescription(): ?string;

    public function getInputClass(): ?string;

    public function getInput(int $post_id): string;
}
