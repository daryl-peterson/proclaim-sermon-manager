<?php

namespace DRPSermonManager\Core\Interfaces;

/**
 * @author daryl
 */
interface VimeoDataInterface
{
    /**
     * Initialize object.
     *
     * @since 1.0.0
     *
     * @param array $data Object properties
     */
    public function __construct(array $data);

    /**
     * Get object field names.
     *
     * @since 1.0.0
     *
     * @return array Array of field names
     */
    public static function getFields();
}
