<?php
namespace App\Authenticator;

use App\Exception\InvalidTokenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class TokenAuthenticator extends AbstractAuthenticator
{
    /**
     * 1. Est-ce que cet authentificateur doit s'activer pour cette requête ?
     * On vérifie si le header "Authorization" contenant "Bearer " est présent.
     */
    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization')
            && str_starts_with($request->headers->get('Authorization'), 'Bearer ');
    }

    /**
     * 2. Si "supports" retourne true, on extrait le token et on crée un Passport.
     */
    public function authenticate(Request $request): Passport
    {
        $authorizationHeader = $request->headers->get('Authorization');
        $token = substr($authorizationHeader, 7); // On retire "Bearer " pour isoler le jeton

        if (empty($token)) {
            throw new  InvalidTokenException();
        }

        // Ici, on valide l'utilisateur lié à ce token.
        // Hypothèse : votre UserProvider sait chercher un utilisateur par son API token.
        return new SelfValidatingPassport(
            new UserBadge($token, function (string $userIdentifier) {
                // Remplacez cette logique par votre recherche en BDD (ex: via un UserRepository)
                // $user = $this->userRepository->findOneBy(['apiToken' => $userIdentifier]);

                // Si l'utilisateur n'existe pas ou le token a expiré :
                // throw new UserNotFoundException();

                return $user;
            })
        );
    }

    /**
     * 3. Que faire si l'authentification réussit ?
     * Pour une API, on ne fait rien (null), on laisse la requête continuer vers le contrôleur.
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    /**
     * 4. Que faire si l'authentification échoue ?
     * On renvoie une réponse JSON propre avec un code 401 Unauthorized.
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
