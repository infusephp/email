<?php

namespace App\Email\Driver;

use Infuse\Utility;

class NullDriver implements DriverInterface
{
    public function send(array $message)
    {
        $result = [];
        $to = (array) array_value($message, 'to');
        foreach ($to as $item) {
            $result[] = [
                '_id' => Utility::guid(false),
                'email' => $item['email'],
                'status' => 'sent',
            ];
        }

        return $result;
    }
}
