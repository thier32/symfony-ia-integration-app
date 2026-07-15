<?php
namespace App\Controller\Impl;

use App\Business\IProductBusiness;
use App\Controller\IProductController;
use App\Dto\Product\ProductCreateDto;
use App\Entity\Product;
use App\Response\ProductResponseDto;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController implements IProductController
{

    public function __construct(private IProductBusiness $productBusiness)
    {
    }




    #[OA\Post(
        path: '/api/product',
        summary: 'Enregistrer un nouveau produit',
        description: 'Enregister un nouveau produit.',
        operationId: 'createProductTransaction'
    )]
    #[OA\Tag(name: 'Produits')]
    #[OA\RequestBody(
        description: 'Données requises pour initialiser un produit.',
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string', description: 'Nom du produit', example: 'Produit test 1'),
                new OA\Property(property: 'sku', type: 'string', description: 'Code produit', example: 'PT1')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Produit enregistré avec succès.',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'product', ref: new Model(type: Product::class, groups: ['product_read']))
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
    #[Route('/api/product', name: 'api_create_product', methods: ['POST'])]
    public function createProduct(#[MapRequestPayload] ProductCreateDto $productCreateDto): ProductResponseDto
    {
        return new ProductResponseDto(
            $this->productBusiness->createProduct(
                $productCreateDto
            )
        );
    }

    #[OA\Get(
        path: '/api/product',
        summary: 'Lister les produits',
        description: 'Lister les produits présents dans  le système',
        operationId: 'listProductTransaction'
    )]
    #[OA\Tag(name: 'Produits')]
    #[OA\Response(
        response: 201,
        description: 'Produit enregistré avec succès.',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'product', ref: new Model(type: Product::class, groups: ['product_read']))
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
    #[Route('/api/product', name: 'api_list_product', methods: ['GET'])]
    public function listProduct(array $searchCriteria = []): ProductResponseDto
    {
        return new ProductResponseDto(
            $this->productBusiness->listProducts($searchCriteria)
        );
    }

}
