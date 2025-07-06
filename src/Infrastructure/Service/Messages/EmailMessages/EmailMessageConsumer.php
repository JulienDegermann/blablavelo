<?php


namespace App\Infrastructure\Service\Messages\EmailMessages;

use Error;
use Throwable;
use Psr\Log\LoggerInterface;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Infrastructure\Service\Messages\MessageConnexionInterface;
use App\Infrastructure\Service\Messages\EmailMessages\RemoveRideEmailMessage\RemoveRideConsumerInterface;
use App\Infrastructure\Service\Messages\EmailMessages\DeleteAccountEmailMessage\DeleteAccountConsumerInterface;
use App\Infrastructure\Service\Messages\EmailMessages\ResetPasswordEmailMessage\ResetPasswordConsumerInterface;
use App\Infrastructure\Service\Messages\EmailMessages\AddParticipantEmailMessage\AddParticipantConsumerInterface;
use App\Infrastructure\Service\Messages\EmailMessages\EmailVerificationEmailMessage\EmailVerificationConsumerInterface;
use App\Infrastructure\Service\Messages\EmailMessages\RemoveParticipantEmailMessage\RemoveParticipantConsumerInterface;


/**
 * Class EmailMessageConsumer - consume messages from 'send_message' queue and routes to appropiated service
 */
#[AsCommand(name: 'app:email_mesasge_consumer')]
final class EmailMessageConsumer extends Command
{

    public function __construct(
        private readonly MessageConnexionInterface $connexion,
        private readonly LoggerInterface $logger,
        private readonly DeleteAccountConsumerInterface $delete_account,
        private readonly EmailVerificationConsumerInterface $email_verification,
        private readonly ResetPasswordConsumerInterface $reset_password,
        private readonly AddParticipantConsumerInterface $add_participant,
        private readonly RemoveParticipantConsumerInterface $remove_participant,
        private readonly RemoveRideConsumerInterface $remove_ride,
    ) {
        parent::__construct();
    }

    public function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {

        $channel = $this->connexion->getChannel();

        $channel->queue_declare('send_email', false, true, false, false, false);
        
        $this->logger->info('consumer is running');

        $callback = function ($msg) {
            try {
                $this->logger->info($msg->getBody());
                $datas = json_decode($msg->getBody(), true);
                $type = $datas['type'] ?? null;
                $this->logger->info('TYPE = ' . strval($datas['type']));
                $this->logger->info('ID : ' . strval($datas['id']));

                $id = intval($datas['id']) ?? null;

                if (!$type || !$id) {
                    throw new InvalidArgumentException('Datas are missing');
                }

                switch ($type) {
                    case 'email_verification':
                        ($this->email_verification)($id);
                        break;
                    case 'reset_password':
                        ($this->reset_password)($id);
                        break;
                    case 'delete_account':

                        $user = $datas['user'] ?? null;
                        if (!$user) {
                            throw new InvalidArgumentException('User datas are missing');
                        }
                        ($this->delete_account)($user);
                        break;
                    case 'add_participant':
                        $participant_id = $datas['participant_id'] ?? null;
                        if (!$participant_id) {
                            throw new InvalidArgumentException('Participant datas are missing');
                        }
                        ($this->add_participant)($id, $participant_id);
                        break;
                    case 'remove_participant':
                        $participant_id = $datas['participant_id'] ?? null;
                        if (!$participant_id) {
                            throw new InvalidArgumentException('Participant datas are missing');
                        }
                        ($this->remove_participant)($id, $participant_id);
                        break;
                    case 'remove_ride':
                        ($this->remove_ride)($id);
                        break;
                    default:
                        $this->logger->error('====== no data =====');
                        break;
                }

                $msg->ack();
            } catch (Throwable $e) {
                $this->logger->error('ERROR RABBIT MQ : ' . $e->getMessage());
                $msg->nack(false, false);
            }
        };

        $channel->basic_consume('send_email', '', false, false, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        return Command::SUCCESS;
    }
}
