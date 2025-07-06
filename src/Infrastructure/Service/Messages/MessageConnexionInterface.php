<?php

namespace App\Infrastructure\Service\Messages;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Interface - create AMQP connexion with RabbitMQ
 */
interface MessageConnexionInterface
{
    public function getChannel(): AMQPChannel;
}
