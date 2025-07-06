<?php

namespace App\Infrastructure\Service\Messages\EmailMessages;

use Psr\Log\LoggerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use App\Infrastructure\Service\Messages\MessageConnexionInterface;

/**
 * Abstract - common setting for publish AMQP messages
 */
abstract class SendEmailPublisherAbstract
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly MessageConnexionInterface $connexion,
    ) {
    }
    
    abstract protected function getType(): string;


    /**
     * Queue message for async e-mail sending
     * @param int $id - id of main data (Ride or User) (don't use for deletion)
     * @param array $more - extra datas for more specific usage (such add/remove participant)
     * @return void
     */
    public function __invoke(int $id, array $more = [] ): void
    {
        $datas = array_merge([
            'type' => $this->getType(),
            'id' => $id
        ], $more);

        // dd($datas);
        // dd(json_encode($datas));

        $message = new AMQPMessage(json_encode($datas, JSON_THROW_ON_ERROR), [
            'content_type' => 'application/json',
            'delivery_mode' => 2
        ]);
        $this->connexion->getChannel()->queue_declare('send_email', false, true, false, false);

        $this->connexion->getChannel()->basic_publish($message, '', 'send_email');
    }
}
