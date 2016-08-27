<?php

namespace Infuse\Email;

use Infuse\HasApp;
use Infuse\Queue;
use Infuse\Queue\Message;

class MailerService
{
    use HasApp;

    public function __construct($app)
    {
        $this->setApp($app);

        Queue::listen(Mailer::QUEUE_NAME, [$this, 'processEmail']);
    }

    public function __invoke($app)
    {
        return new Mailer($app['config']->get('email'));
    }

    /**
     * Handles an email message coming off the queue.
     *
     * @param Message $message
     */
    public function processEmail(Message $message)
    {
        $this->app['mailer']->processEmail($message);
    }
}
