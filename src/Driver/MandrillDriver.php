<?php

namespace App\Email\Driver;

use Mandrill;

class MandrillDriver implements DriverInterface
{
    /**
     * @var Mandrill
     */
    private $mandrill;

    public function __construct(array $settings)
    {
        $this->mandrill = new Mandrill($settings['key']);
    }

    /**
     * Gets the Mandrill instance.
     *
     * @return Mandrill
     */
    public function getMandrill()
    {
        return $this->mandrill;
    }

    /**
     * Sets the Mandrill instance.
     *
     * @param Mandrill $mandrill
     *
     * @return self
     */
    public function setMandrill($mandrill)
    {
        $this->mandrill = $mandrill;

        return $this;
    }

    public function send(array $message)
    {
        $to = (array) array_value($message, 'to');
        if (count($to) === 0) {
            return [];
        }

        return $this->mandrill->messages->send($message);
    }
}
