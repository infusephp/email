<?php

namespace App\Email;

use App\Email\Services\EmailService;
use Infuse\Queue;
use Infuse\Queue\Message;

class Controller
{
    use \InjectApp;

    public function middleware($req, $res)
    {
        $this->app['mailer'] = function ($app) {
            return new EmailService($app['config']->get('email'), $app);
        };

        Queue::listen(EmailService::QUEUE_NAME, [$this, 'processEmail']);
    }

    public function processEmail(Message $message)
    {
        $mailer = $this->app['mailer'];

        // uncompress the message variables
        $body = json_decode($message->getBody());
        $variables = $mailer->uncompressMessage($body->m);

        $mailer->sendEmail($body->t, $variables);
    }
}
