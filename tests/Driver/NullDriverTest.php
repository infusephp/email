<?php

use Infuse\Email\Driver\NullDriver;

class NullDriverTest extends PHPUnit_Framework_TestCase
{
    public function testSend()
    {
        $driver = new NullDriver();

        $message = [
            'to' => [
                [
                    'name' => 'Name',
                    'email' => 'to@example.com',
                ],
                [
                    'name' => 'BCC',
                    'email' => 'bcc@example.com',
                    'type' => 'bcc',
                ],
            ],
            'headers' => [
                'Reply-To' => 'replyto@example.com',
            ],
            'from_email' => 'from@example.com',
            'from_name' => 'From',
            'subject' => 'Subject',
            'html' => 'html',
            'text' => 'text',
        ];

        $expected = [
            [
                'email' => 'to@example.com',
                'status' => 'sent',
            ],
            [
                'email' => 'bcc@example.com',
                'status' => 'sent',
            ],
        ];

        $result = $driver->send($message);
        $this->assertEquals(32, strlen($result[0]['_id']));
        unset($result[0]['_id']);
        $this->assertEquals(32, strlen($result[1]['_id']));
        unset($result[1]['_id']);
        $this->assertEquals($expected, $result);
    }

    public function testSendEmpty()
    {
        $driver = new NullDriver();
        $this->assertEquals([], $driver->send([]));
    }
}
