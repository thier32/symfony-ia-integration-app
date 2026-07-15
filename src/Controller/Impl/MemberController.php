<?php
namespace App\Controller\Impl;

use App\Business\IMemberBusiness;
use App\Controller\IMemberController;
use App\Dto\Member\MemberCreateDto;
use App\Dto\Member\MemberDto;
use App\Entity\Member;
use App\Response\MemberResponseDto;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class MemberController extends AbstractController implements IMemberController
{

    public function __construct(private IMemberBusiness $memberBusiness)
    {
    }


    #[OA\Post(
        path: '/api/admin',
        summary: 'Enregistrer un nouveau administrateur',
        description: 'Ajouter un nouveau administrateur.',
        operationId: 'createMembreTransaction'
    )]
    #[OA\Tag(name: 'Administrateurs')]
    #[OA\RequestBody(
        description: 'Données requises pour initialiser un administrateur.',
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string', description: 'Nom de l\'administrateur', example: 'Gustave Tse'),
                new OA\Property(property: 'email', type: 'string', description: 'Email de l\'administrateur', example: 'tiwana03@benshelf.com')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Administrateur enregistré avec succès.',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'administrateur', ref: new Model(type: Member::class, groups: ['member_read']))
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Données invalides ou manquantes (ex: format de date incorrect).'
    )]
    #[OA\Response(
        response: 401,
        description: 'Authentification JWT manquante ou invalide.'
    )]
    #[OA\Response(
        response: 409,
        description: 'Conflit métier (ex: le livre est déjà emprunté ou le compte de l\'administrateur est bloqué).',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'string', example: 'ADMIN_ALREADY_EXISTS'),
                new OA\Property(property: 'message', type: 'string', example: 'Cet administrateur existe déjà.')
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Erreur interne du serveur lors du traitement de la transaction.'
    )]
    #[Route('/api/admin', name: 'api_create_admin', methods: ['POST'])]
    public function createMember(#[MapRequestPayload] MemberCreateDto $memberCreateDto): MemberResponseDto
    {
        return new MemberResponseDto(
            $this->memberBusiness->createMember(
                $memberCreateDto
            )
        );
    }

    #[OA\Get(
        path: '/api/admin',
        summary: 'Lister les administrateurs',
        description: 'Liste les administrateurs.',
        operationId: 'listMemberTransaction'
    )]
    #[OA\Tag(name: 'Administrateurs')]
    #[OA\Response(
        response: 201,
        description: 'Liste des administrateurs affichée avec succès.',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'member', ref: new Model(type: MemberDto::class, groups: ['book_read']))
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Données invalides ou manquantes (ex: format de date incorrect).'
    )]
    #[OA\Response(
        response: 401,
        description: 'Authentification JWT manquante ou invalide.'
    )]
    #[OA\Response(
        response: 409,
        description: 'Conflit métier (ex: le livre est déjà emprunté ou le compte du membre est bloqué).',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'string', example: 'BOOK_ALREADY_BORROWED'),
                new OA\Property(property: 'message', type: 'string', example: 'Cet ouvrage n\'est pas disponible pour le moment.')
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Erreur interne du serveur lors du traitement de la transaction.'
    )]
    #[Route('/api/admin', name: 'api_list_admin', methods: ['GET'])]
    public function listMember(array $searchCriteria = []): MemberResponseDto
    {
        return new MemberResponseDto(
            $this->memberBusiness->listMember($searchCriteria)
        );
    }
}
