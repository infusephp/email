<?php

namespace Infuse\Email\Driver;

interface DriverInterface
{
    /**
     * Sends a message.
     *
     * @return array resulting messages
     */
    public function send(array $message);
}
