<?php

return [
    'views' => [
        'engine' => 'Infuse\ViewEngine\PHP',
    ],
    'services' => [
        'queue_driver' => 'Infuse\Services\QueueDriver',
    ],
    'queue' => [
        'driver' => 'Infuse\Queue\Driver\SynchronousDriver',
    ],
];
