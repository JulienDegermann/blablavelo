<?php

namespace App\Domain\User\UseCase\CheckUserIsVerified;

use App\Domain\User\Contrat\CheckUserIsVerifiedInterface;
use App\Domain\User\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CheckUserIsVerified implements CheckUserIsVerifiedInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    public function __invoke(User $user): bool|string
    {
        $return = true;
        $string = 'Veuillez vérifier votre e-mail pour profiter de l\'application. 
            Pas de mail ? <a class="px-2 text-primary fw-bold" title="demander un nouveau lien de validation" href=" ' . $this->urlGenerator->generate("app_new_token", [], UrlGeneratorInterface::ABSOLUTE_URL) . '">Générer un lien</a>';
            

        $return = $user->getIsVerified() ? true : $string;

        return $return;
    }
}
