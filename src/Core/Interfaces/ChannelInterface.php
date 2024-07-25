<?php

namespace DRPSermonManager\Core\Interfaces;

/**
 * @author Daryl Peterson
 */
interface ChannelInterface
{
    public function get($channel_id);

    public function create($schema);

    public function delete();
}
