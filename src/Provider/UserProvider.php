<?php

namespace App\Provider;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function __construct(private UserRepository $userRepository) {}

    public function refreshUser(UserInterface $user): UserInterface
    {
        // Pour une API stateless, on peut simplement retourner le user
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }

    /**
     * C'est cette méthode que l'authentificateur appellera.
     * Le $identifier sera ici votre Token.
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        // Votre logique sur mesure ici (ex: chercher par token, vérifier l'expiration...)
        $user = $this->userRepository->findOneBy(['apiToken' => $identifier]);

        if (!$user) {
            throw new UserNotFoundException('Token invalide ou utilisateur introuvable.');
        }

        return $user;
    }
}
