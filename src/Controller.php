<?php

namespace app\email;

use InjectApp;
use app\email\services\EmailService;

class Controller
{
    use InjectApp;

    public function middleware($req, $res)
    {
        $this->app[ 'mailer' ] = function ($app) {
            return new EmailService( $app[ 'config' ]->get( 'email' ), $app );
        };
    }

    public function processEmail($queue, $message)
    {
        // messy hack to convert an object to an array
        $m = json_decode( json_encode( $message->body->m ), true );

        if ( $this->app[ 'mailer' ]->sendEmail( $message->body->t, $m ) ) {
            if( $queue->type() == QUEUE_TYPE_SYNCHRONOUS )
                $queue->deleteMessage( EMAIL_QUEUE_NAME, $message );
        }
    }
}
