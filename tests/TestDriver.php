<?php

use App\Email\Driver\DriverInterface;
use Infuse\Utility;

class TestDriver implements DriverInterface
{
    public function send(array $message)
    {
        // build recipients
        $to = [];
        $bcc = [];
        foreach ((array) $message['to'] as $item) {
            $type = array_value($item, 'type');
            if ($type == 'bcc') {
                $bcc[$item['email']] = $item['name'];
            } else {
                $to[$item['email']] = $item['name'];
            }
        }

        $result = [];
        foreach (array_merge($to, $bcc) as $email => $name) {
            $result[] = array_replace($message, [
                'email' => $email,
                '_id' => Utility::guid(false),
                'to_alt' => $to,
                'status' => 'sent', ]);
        }

        return $result;
    }
}
