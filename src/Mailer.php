<?php

namespace App\Email;

class Mailer
{
    public function __invoke($app)
    {
        return new MailerService($app['config']->get('email'), $app);
    }
}
