<?php

use Infuse\Email\Driver\MandrillDriver;

class MandrillDriverTest extends PHPUnit_Framework_TestCase
{
    public function testMandrill()
    {
        $driver = new MandrillDriver(['key' => 'test']);
        $this->assertInstanceOf('Mandrill', $driver->getMandrill());
    }

    public function testSend()
    {
        $driver = new MandrillDriver(['key' => 'test']);

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

        $mandrill = Mockery::mock();
        $mandrill->messages = Mockery::mock();
        $mandrill->messages
                 ->shouldReceive('send')
                 ->withArgs([$message])
                 ->andReturn($expected);
        $driver->setMandrill($mandrill);

        $result = $driver->send($message);
        $this->assertEquals($expected, $result);
    }

    public function testSendEmpty()
    {
        $driver = new MandrillDriver(['key' => 'test']);
        $this->assertEquals([], $driver->send([]));
    }
}
