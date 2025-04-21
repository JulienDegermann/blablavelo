<?php

namespace App\Domain\Message\UseCase\SendMessage;

use App\Domain\Message\Contrat\MessageRepositoryInterface;
use App\Domain\Message\Contrat\SendMessageInterface;
use App\Domain\Message\Message;
use App\Domain\User\User;
use Exception;
use InvalidArgumentException;

final class SendMessage implements SendMessageInterface
{
    public function __construct(
        private readonly MessageRepositoryInterface $messageRepo
    ) {}

    public function __invoke(SendMessageInput $input): string
    {

        // title validation
        if (!$input->getTitle() || ($input->getTitle() !== 'bug' && $input->getTitle() !== 'suggestion')) {
            throw new InvalidArgumentException('Objet du message invalide.');
        }

        // text validation
        if (
            strlen($input->getText()) < 20 ||
            strlen($input->getText()) > 500
        ) {
            throw new InvalidArgumentException('Contenu du message est invalide. Il doit contenir entre 20 et 500 caractères.');
        }

        if (
            !$input->getText() ||
            !preg_match('/^(?![×Þß÷þø])[0-9a-zA-ZÀ-ÿ\-\s,?\'()]{2,}$/u', $input->getText())
        ) {
            throw new InvalidArgumentException('Contenu du message est invalide. (adresses e-mail, sites web interdits.)');
        }

        // user validation
        if (
            !$input->getUser() ||
            !$input->getUser() instanceof User
        ) {
            throw new InvalidArgumentException('Utilisateur invalide. ');
        }

        $message = new Message(
            $input->getTitle(),
            $input->getText(),
            $input->getUser(),
        );

        try {
            $this->messageRepo->save($message);
        } catch (Exception) {
            throw new InvalidArgumentException('Une erreur est survenue, essaie plus tard');
        };

        return "Ton message a bien été envoyé 👍.";
    }
}
