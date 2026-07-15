<?php
namespace App\Listener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;


#[AsEventListener(event: 'kernel.exception', method: 'onKernelException')]
class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

            // On cible uniquement les requêtes de notre API
        if (!str_starts_with($request->getPathInfo(), '/api')) {
            return;
        }

        // Si c'est une exception HTTP (ex: 404, 405), on garde son code, sinon 500
        $statusCode = $exception instanceof HttpExceptionInterface
            ? $exception->getStatusCode()
            : JsonResponse::HTTP_INTERNAL_SERVER_ERROR;

        // Formatage de la réponse pour le frontend React
        $responseData = [
            'code' => $statusCode,
            'success' => false,
            'message' => 'Une erreur interne est survenue sur le serveur.',
            'error'   => $exception->getMessage()
        ];

        // Renvoyer la réponse JSON stoppe le rendu de la page d'erreur d'origine
        $response = new JsonResponse($responseData, $statusCode);
        $event->setResponse($response);
    }
}

