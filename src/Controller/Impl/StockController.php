<?php
namespace App\Controller\Impl;

use App\Business\IStockMovementBusiness;
use App\Controller\IStockController;
use App\Dto\StockMovement\StockMovementCreateDto;
use App\Entity\StockMovement;
use App\Response\StockMovementResponseDto;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class StockController extends AbstractController implements IStockController
{

    public function __construct(private IStockMovementBusiness $stockMovementBusiness)
    {
    }


    #[OA\Post(
        path: '/api/stock/entry',
        summary: 'Enregistrer un nouveau stock',
        description: 'Enregister un nouveau stock.',
        operationId: 'createStockEntry'
    )]
    #[OA\Tag(name: 'StockMovements')]
    #[OA\RequestBody(
        description: 'Données requises pour enregistrer un nouveau stock.',
        required: true,
        content: new OA\JsonContent(
            required: ['productId', 'quantity'],
            properties: [
                new OA\Property(property: 'productId', type: 'integer', description: 'Identifiant Produit à déstocker', example:239999999),
                new OA\Property(property: 'quantity', type: 'integer', description: 'Quantité à ajouter', example: 50),
                new OA\Property(property: 'type', type: 'string', description: 'Type de l\'opération',enum: ['MANUAL_ADJUSTMENT','INCOMING_SHIPMENT','REFILL_PRODUCT'], example: 'REFILL_PRODUCT')
                ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Stock ajouté avec succès.',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'stockMovement', ref: new Model(type: StockMovement::class, groups: ['product_read']))
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
                new OA\Property(property: 'error', type: 'string', example: 'PRODUCT_ALREADY_EXISTS'),
                new OA\Property(property: 'message', type: 'string', example: 'Ce produit est déjà présent.')
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Erreur interne du serveur lors du traitement de la transaction.'
    )]
    #[Route('/api/stock/entry', name: 'api_entry_stock', methods: ['POST'])]
    public function receiveStockProduct(#[MapRequestPayload] StockMovementCreateDto $movementCreateDto): StockMovementResponseDto
    {
        return new StockMovementResponseDto(
            $this->stockMovementBusiness->receiveStock($movementCreateDto)
        );
    }

    #[OA\Post(
        path: '/api/stock/exit',
        summary: 'Enregistrer une sortie de stock',
        description: 'Enregister une sortie de stock.',
        operationId: 'exitStockTransaction'
    )]
    #[OA\Tag(name: 'StockMovements')]
    #[OA\RequestBody(
        description: 'Données requises pour enregistrer une sortie de stock.',
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'productId', type: 'integer', description: 'Identifiant Produit à déstocker', example:239999999),
                new OA\Property(property: 'quantity', type: 'integer', description: 'Quantité à déstocker', example: 50),
                new OA\Property(property: 'type', type: 'string', description: 'Type de l\'opération',enum: ['MANUAL_ADJUSTMENT','ORDER_SALE'], example: 'ORDER_SALE')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Stock retiré avec succès.',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'stockMovement', ref: new Model(type: StockMovement::class, groups: ['product_read']))
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
                new OA\Property(property: 'error', type: 'string', example: 'PRODUCT_ALREADY_EXISTS'),
                new OA\Property(property: 'message', type: 'string', example: 'Ce produit est déjà présent.')
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Erreur interne du serveur lors du traitement de la transaction.'
    )]
    #[Route('/api/stock/exit', name: 'api_exit_stock', methods: ['POST'])]
    public function sellStockProduct(#[MapRequestPayload] StockMovementCreateDto $movementCreateDto): StockMovementResponseDto
    {
        return new StockMovementResponseDto(
            $this->stockMovementBusiness->sellStock($movementCreateDto)
        );
    }
}
