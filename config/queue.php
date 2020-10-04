<?php
return [
    'class' => \yii\queue\amqp_interop\Queue::class,
    'host' => $_ENV['RABBIT_MQ_QUEUE_HOST'],
    'port' => $_ENV['RABBIT_MQ_QUEUE_PORT'],
    'user' => $_ENV['RABBIT_MQ_QUEUE_USERNAME'],
    'password' => $_ENV['RABBIT_MQ_QUEUE_PASSWORD'],
    'queueName' => 'main',
    'vhost' => $_ENV['RABBIT_MQ_QUEUE_V_HOST'],
    'driver' => yii\queue\amqp_interop\Queue::ENQUEUE_AMQP_LIB,
];
