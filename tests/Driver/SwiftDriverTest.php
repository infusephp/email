<?php

use App\Email\Driver\SwiftDriver;

class SwiftDriverTest extends PHPUnit_Framework_TestCase
{
    public function testGetSwift()
    {
        $settings = [
            'host' => 'localhost',
            'port' => 25,
            'username' => '',
            'password' => '',
        ];
        $driver = new SwiftDriver($settings);

        $this->assertInstanceOf('Swift_Mailer', $driver->getSwift());
    }

    public function testSend()
    {
        $transport = Mockery::mock('Swift_Transport');
        $transport->shouldReceive('isStarted')
                  ->andReturn(true);
        $transport->shouldReceive('send')
                  ->andReturn(true);

        $driver = new SwiftDriver([], $transport);

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
        $transport = Mockery::mock('Swift_Transport');
        $driver = new SwiftDriver([], $transport);
        $this->assertEquals([], $driver->send([]));
    }
}
