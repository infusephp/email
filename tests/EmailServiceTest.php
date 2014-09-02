<?php

use app\email\services\EmailService;

class EmailServiceTest extends \PHPUnit_Framework_TestCase
{
    private static $emailService;
    private static $qListeners;

    public static function setUpBeforeClass()
    {
        self::$emailService = new EmailService( [ 'type' => 'nop' ], TestBootstrap::app() );

        // remove queue listeners
        // Queue::configure( [ 'listeners' => [] ] );
    }

    public static function tearDownAfterClass()
    {
        // add back queue listeners
        // Queue::configure( [ 'listeners' => Config::get( 'queue.listeners' ) ] );
    }

    public function testQueueEmail()
    {
        $options = [
            'to' => [
                [
                    'email' => 'test@example.com',
                    'name' => 'Teddy' ],
                [
                    'email' => 'test2@example.com',
                    'name' => 'Not Teddy' ] ],
            'from_email' => 'from+test@example.com',
            'from_name' => 'Testing',
            'html' => '<strong>test</strong>',
            'text' => 'test'
        ];

        $this->assertEquals( true, self::$emailService->queueEmail( false, $options ) );

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
                        'name' => 'Teddy' ],
                    [
                        'email' => 'test2@example.com',
                        'name' => 'Not Teddy' ] ],
                'to_alt' => [
                    'test@example.com' => 'Teddy',
                    'test2@example.com' => 'Not Teddy' ],
                'status' => 'sent' ],
            [
                'html' => '<strong>test</strong>',
                'text' => 'test',
                'from_email' => 'from+test@example.com',
                'from_name' => 'Testing',
                'to' => [
                    [
                        'email' => 'test@example.com',
                        'name' => 'Teddy' ],
                    [
                        'email' => 'test2@example.com',
                        'name' => 'Not Teddy' ] ],
                'to_alt' => [
                    'test@example.com' => 'Teddy',
                    'test2@example.com' => 'Not Teddy' ],
                'status' => 'sent' ] ];

        $options = [
            'to' => [
                [
                    'email' => 'test@example.com',
                    'name' => 'Teddy' ],
                [
                    'email' => 'test2@example.com',
                    'name' => 'Not Teddy' ] ],
            'from_email' => 'from+test@example.com',
            'from_name' => 'Testing',
            'html' => '<strong>test</strong>',
            'text' => 'test'
        ];

        $this->assertEquals( $expected, self::$emailService->sendEmail( false, $options ) );
    }

    public function testSendEmailTemplate()
    {
        $options = [
            'who' => 'World',
            'to' => [
                [
                    'email' => 'test@example.com',
                    'name' => 'Teddy' ] ],
            'from_email' => 'from+test@example.com',
            'from_name' => 'Testing'
        ];
        $result = self::$emailService->sendEmail( 'test', $options );

        $this->assertEquals( '<html>Hello, World!</html>', $result[ 0 ][ 'html' ] );
        $this->assertEquals( 'Hello, World!', $result[ 0 ][ 'text' ] );
    }
}
