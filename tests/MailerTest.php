<?php

use Infuse\Email\Mailer;
use Infuse\Test;

include 'TestDriver.php';

class MailerTest extends PHPUnit_Framework_TestCase
{
    private static $mailer;
    private static $qListeners;

    public static function setUpBeforeClass()
    {
        $settings = [
            'driver' => 'TestDriver',
            'from_email' => 'from+test@example.com',
            'from_name' => 'Testing',
        ];

        self::$mailer = new Mailer($settings);
    }

    public function testDriver()
    {
        $this->assertInstanceOf('TestDriver', self::$mailer->getDriver());
    }

    public function testTypeDeprecated()
    {
        $mailer = new Mailer(['type' => 'nop']);
        $this->assertInstanceOf('Infuse\Email\Driver\NullDriver', $mailer->getDriver());
    }

    public function testCompression()
    {
        $uncompressed = ['test' => true];
        $compressed = self::$mailer->compressMessage($uncompressed);

        $this->assertNotEquals($uncompressed, $compressed);
        $this->assertEquals($uncompressed, self::$mailer->uncompressMessage($compressed));
    }

    public function testQueueEmail()
    {
        $options = [
            'to' => [
                [
                    'email' => 'test@example.com',
                    'name' => 'Teddy', ],
                [
                    'email' => 'test2@example.com',
                    'name' => 'Not Teddy', ], ],
            'from_email' => 'from+test@example.com',
            'from_name' => 'Testing',
            'html' => '<strong>test</strong>',
            'text' => 'test',
        ];

        $message = self::$mailer->queueEmail(false, $options);
        $this->assertInstanceOf('Infuse\Queue\Message', $message);

        // test if in queue
        // TODO
    }

    public function testQueue()
    {
        $message = [
            'to' => [
                [
                    'email' => 'test@example.com',
                    'name' => 'Teddy', ],
                [
                    'email' => 'test2@example.com',
                    'name' => 'Not Teddy', ], ],
            'from_email' => 'from+test@example.com',
            'from_name' => 'Testing',
            'html' => '<strong>test</strong>',
            'text' => 'test',
        ];

        $message = self::$mailer->queue($message);
        $this->assertInstanceOf('Infuse\Queue\Message', $message);

        // test if in queue
        // TODO
    }

    public function testSendEmail()
    {
        $expected = [
            [
                'html' => '<strong>test</strong>',
                'text' => 'test',
                'from_email' => 'from+test@example.com',
                'from_name' => 'Testing',
                'to' => [
                    [
                        'email' => 'test@example.com',
                        'name' => 'Teddy', ],
                    [
                        'email' => 'test2@example.com',
                        'name' => 'Not Teddy', ], ],
                'to_alt' => [
                    'test@example.com' => 'Teddy',
                    'test2@example.com' => 'Not Teddy', ],
                'status' => 'sent',
                'email' => 'test@example.com', ],
            [
                'html' => '<strong>test</strong>',
                'text' => 'test',
                'from_email' => 'from+test@example.com',
                'from_name' => 'Testing',
                'to' => [
                    [
                        'email' => 'test@example.com',
                        'name' => 'Teddy', ],
                    [
                        'email' => 'test2@example.com',
                        'name' => 'Not Teddy', ], ],
                'to_alt' => [
                    'test@example.com' => 'Teddy',
                    'test2@example.com' => 'Not Teddy', ],
                'status' => 'sent',
                'email' => 'test2@example.com', ], ];

        $options = [
            'to' => [
                [
                    'email' => 'test@example.com',
                    'name' => 'Teddy', ],
                [
                    'email' => 'test2@example.com',
                    'name' => 'Not Teddy', ], ],
            'from_email' => 'from+test@example.com',
            'from_name' => 'Testing',
            'html' => '<strong>test</strong>',
            'text' => 'test',
        ];

        $result = self::$mailer->sendEmail(false, $options);
        foreach ($result as &$message) {
            unset($message['_id']);
        }
        $this->assertEquals($expected, $result);
    }

    public function testSendEmailTemplate()
    {
        $options = [
            'who' => 'World',
            'to' => [
                [
                    'email' => 'test@example.com',
                    'name' => 'Teddy', ], ],
        ];
        $result = self::$mailer->sendEmail('test', $options);

        $this->assertEquals("<html>Hello, World!</html>\n", $result[0]['html']);
        $this->assertEquals("Hello, World!\n", $result[0]['text']);
    }

    public function testSend()
    {
        $expected = [
            [
                'html' => '<strong>test</strong>',
                'text' => 'test',
                'from_email' => 'from+test@example.com',
                'from_name' => 'Testing',
                'to' => [
                    [
                        'email' => 'test@example.com',
                        'name' => 'Teddy', ],
                    [
                        'email' => 'test2@example.com',
                        'name' => 'Not Teddy', ], ],
                'to_alt' => [
                    'test@example.com' => 'Teddy',
                    'test2@example.com' => 'Not Teddy', ],
                'status' => 'sent',
                'email' => 'test@example.com', ],
            [
                'html' => '<strong>test</strong>',
                'text' => 'test',
                'from_email' => 'from+test@example.com',
                'from_name' => 'Testing',
                'to' => [
                    [
                        'email' => 'test@example.com',
                        'name' => 'Teddy', ],
                    [
                        'email' => 'test2@example.com',
                        'name' => 'Not Teddy', ], ],
                'to_alt' => [
                    'test@example.com' => 'Teddy',
                    'test2@example.com' => 'Not Teddy', ],
                'status' => 'sent',
                'email' => 'test2@example.com', ], ];

        $message = [
            'to' => [
                [
                    'email' => 'test@example.com',
                    'name' => 'Teddy', ],
                [
                    'email' => 'test2@example.com',
                    'name' => 'Not Teddy', ], ],
            'from_email' => 'from+test@example.com',
            'from_name' => 'Testing',
            'html' => '<strong>test</strong>',
            'text' => 'test',
        ];

        $result = self::$mailer->send($message);
        foreach ($result as &$line) {
            unset($line['_id']);
        }
        $this->assertEquals($expected, $result);
    }

    public function testSendTemplate()
    {
        $message = [
            'to' => [
                [
                    'email' => 'test@example.com',
                    'name' => 'Teddy', ], ],
        ];
        $vars = ['who' => 'World'];
        $result = self::$mailer->send($message, 'test', $vars);

        $this->assertEquals("<html>Hello, World!</html>\n", $result[0]['html']);
        $this->assertEquals("Hello, World!\n", $result[0]['text']);
    }
}
