<?php
namespace App\Exception;


use Symfony\Component\Security\Core\Exception\AuthenticationException;

class InvalidTokenException extends AuthenticationException
{
    /**
     * Cette méthode définit la clé de message par défaut.
     * Utile pour la traduction ou pour l'affichage brut.
     */
    public function getMessageKey(): string
    {
        return 'Le jeton d\'authentification fourni est invalide ou a expiré.';
    }
}
