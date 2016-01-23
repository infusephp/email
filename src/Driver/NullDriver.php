<?php

namespace App\Email\Driver;

use Infuse\Utility;

class NullDriver implements DriverInterface
{
    public function send(array $message)
    {
        $result = [];
        foreach ($message['to'] as $item) {
            $result[] = [
                '_id' => Utility::guid(false),
                'email' => $item['email'],
                'status' => 'sent',
            ];
        }

        return $result;
    }
}
