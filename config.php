<?php

return [
    'dirs' => [
        'views' => __DIR__.'/tests/views',
    ],
    'services' => [
        'mailer' => 'App\Email\MailerService',
        'queue_driver' => 'Infuse\Services\QueueDriver',
    ],
    'email' => [
        'driver' => 'App\Email\Driver\NullDriver',
    ],
    'queue' => [
        'driver' => 'Infuse\Queue\Driver\SynchronousDriver',
    ],
];
