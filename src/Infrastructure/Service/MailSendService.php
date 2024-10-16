<?php

namespace App\Infrastructure\Service;

use App\Domain\User\User;
use App\Infrastructure\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class MailSendService
{
  private $urlGenerator;
  private $tokenGenerator;
  private $userRepo;
  private $mailer;
  private $from = 'no-reply.blablavelo@julien-degermann.fr';

  public function __construct(
    UrlGeneratorInterface $urlGenerator,
    TokenGeneratorInterface $tokenGenerator,
    UserRepository $userRepo,
    MailerInterface $mailer,
  ) {
    $this->urlGenerator = $urlGenerator;
    $this->tokenGenerator = $tokenGenerator;
    $this->userRepo = $userRepo;
    $this->mailer = $mailer;
  }

  private function setFrom(string $from): self
  {
    $this->from = $from;
    return $this;
  }

  public function emailConfirmation(User $user): string
  {
    // change user verification status
    $token = $this->tokenGenerator->generateToken();
    $user->setToken($token);
    $user->setIsVerified(false);
    $this->userRepo->save($user);

    // create confirmation url
    $url = $this->urlGenerator->generate('app_email_verify', ['token' => $user->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);

    // send email with confirmation url
    $email = new Email();
    $email->from($this->from)
      ->to($user->getEmail())
      ->subject('Confirmation d\'e-mail')
      ->html('<p>Vous pouvez confirmer votre e-mail en cliquant sur le lien suivant :</p> <a href="' . $url . '">cliquez ici</a>');
    $this->mailer->send($email);

    $addFlash = 'Un e-mail de confirmation vous a été envoyé. Veuillez cliquer sur le lien qu\'il contient pour confirmer votre adresse e-mail.';
    return $addFlash;
  }

  public function deleteRideEmail(Collection $participants, User $user): string
  {
    foreach ($participants as $participant) {
      if ($participant != $user) {
        $email = (new Email())
          ->from($this->from)
          ->to($participant->getEmail())
          ->subject('IMPORTANT : Sortie annulée')
          ->html('<p>Bonjour ' . $participant . ', ' . $user . ' a annulé une sortie à laquelle vous êitez inscrit. N\'hésitez pas à retourner sur l\'application pour trouver une nouvelle sortie !</p>
                  <p>Équipe Blabla Vélo</p>');
        $this->mailer->send($email);
      }
    }
    $addFlash = 'La sortie a bien été supprimée. Un email a été envoyé aux participants.';
    return $addFlash;
  }


  public function forgotPasswordEmail(?User $user): string
  {
    if ($user) {
      $token = $this->tokenGenerator->generateToken();
      $user->setToken($token);
      $this->userRepo->save($user);
      $url = $this->urlGenerator->generate('app_pwd_reset', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

      $email = (new Email())
        ->from($this->from)
        ->to($user->getEmail())
        ->subject('Réinitialisation de votre mot de passe Blabla Vélo')
        ->html('<p>Vous pouvez réinitialiser votre mot de passe en cliquant sur le lien suivant :</p> <a href="' . $url . '">cliquez ici</a> <br> <p>Équipe Blabla Vélo</p>');

      $this->mailer->send($email);
    }
    $addFlash = 'Un e-mail a été envoyé à l\'adresse indiquée.';
    return $addFlash;
  }
}
