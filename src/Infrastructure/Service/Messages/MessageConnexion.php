<?php

namespace App\Infrastructure\Service\Messages;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class MessageConnexion - create AMQP connexion with RabbitMQ
 */
final class MessageConnexion extends AMQPStreamConnection implements MessageConnexionInterface
{
    private AMQPChannel $channel;

    public function __construct()
    {
        parent::__construct('rabbit-mq', 5672, 'guest', 'guest');
        $this->channel = $this->channel();
    }


    public function getChannel(): AMQPChannel
    {
        return $this->channel;
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->close();
    }
}
