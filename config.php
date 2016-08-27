<?php

return [
    'dirs' => [
        'views' => __DIR__.'/tests/views',
    ],
    'services' => [
        'mailer' => 'Infuse\Email\MailerService',
        'queue_driver' => 'Infuse\Services\QueueDriver',
    ],
    'email' => [
        'driver' => 'Infuse\Email\Driver\NullDriver',
    ],
    'queue' => [
        'driver' => 'Infuse\Queue\Driver\SynchronousDriver',
    ],
];
