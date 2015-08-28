<?php

namespace app\email;

use app\email\services\EmailService;

class Controller
{
    use \InjectApp;

    public function middleware($req, $res)
    {
        $this->app[ 'mailer' ] = function ($app) {
            return new EmailService($app[ 'config' ]->get('email'), $app);
        };
    }

    public function processEmail($queue, $message)
    {
        $mailer = $this->app['mailer'];

        // uncompress the message variables
        $variables = $mailer->uncompressMessage($message->body->m);

        if ($mailer->sendEmail($message->body->t, $variables)) {
            if ($queue->type() == QUEUE_TYPE_SYNCHRONOUS) {
                $queue->deleteMessage(EMAIL_QUEUE_NAME, $message);
            }
        }
    }
}
