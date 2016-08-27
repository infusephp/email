<?php

use Infuse\Email\MailerService;
use Infuse\Test;

class MailerServiceTest extends PHPUnit_Framework_TestCase
{
    public function testService()
    {
        $this->assertInstanceOf('Infuse\Email\Mailer', Test::$app['mailer']);

        $service = new MailerService(Test::$app);
        $this->assertInstanceOf('Infuse\Email\Mailer', $service(Test::$app));
    }
}
