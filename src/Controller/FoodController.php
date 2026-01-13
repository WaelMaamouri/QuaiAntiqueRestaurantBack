<?php

namespace App\Controller;

use App\Entity\Food;
use App\Repository\FoodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Schema;
use OpenApi\Attributes\Items;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/food', name: 'app_api_food_')]
final class FoodController extends AbstractController
{
    public function __construct(private FoodRepository $repository, private EntityManagerInterface $entityManager, private SerializerInterface $serializer) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }
    #[OA\Post (
        path: "/api/food",
        summary: "Créer un nouveau plat",
        tags: ["Food"],
        security: [
            ["X-AUTH-TOKEN" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "title", type: "string"),
                    new OA\Property(property: "description", type: "text"),
                    new OA\Property(property: "price", type: "integer"),
                    new OA\Property(property: "category_id", type: "integer"),
                    new OA\Property(property: "restaurant_id", type: "integer"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Plat créé avec succès",
    
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property: "title", type: "string"),
                        new OA\Property(property: "description", type:"string"),
                        new OA\Property(property: "price", type: "integer"),
                        new OA\Property(property: "category_id", type: "integer"),
                        new OA\Property(property: "restaurant_id", type: "integer"),
                    ]
                )
                )
        ]
    )]
    #[Route(methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $food = $this->serializer->deserialize($request->getContent(), Food::class, 'json');
        $food->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($food);
        $this->entityManager->flush();

        $responseData = $this->serializer->serialize($food, 'json');

        $location = $this->generateUrl('app_api_food_show', ['id' => $food->getId()]);
        
        return new JsonResponse($responseData, Response::HTTP_CREATED, ['Location' => $location], true);
    }
    #[OA\Get (
        path: "/api/food/{id}",
        summary: "Afficher le plat par ID",
        tags: ["Food"],
        security: [
            ["X-AUTH-TOKEN" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "id", type: "integer"),
                    new OA\Property(property: "title", type: "string"),
                    new OA\Property(property: "description", type: "string"),
                    new OA\Property(property: "price", type: "integer"),
                    new OA\Property(property: "category_id", type: "integer"),
                    new OA\Property(property: "restaurant_id", type: "integer"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Plat récupéré avec succès",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property:"title", type: "string"),
                        new OA\Property(property:"description", type: "string"),
                        new OA\Property(property:"price", type: "integer"),
                        new OA\Property(property:"category_id", type: "integer"),
                        new OA\Property(property:"restaurant_id", type: "integer"),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Plat not found",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "message", type: "string"),
                    ]
                )
            )
        ]
    )]

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
       // Chercer le food id = 1
        $food = $this->repository->find($id);
        if ($food) {
            $responseData = $this->serializer->serialize($food, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }
        
        return new JsonResponse(
            null, 
            Response::HTTP_NOT_FOUND
        );
     
    }

    #[OA\Put(
        path: "/api/food/{id}",
        summary: "Modifier un plat",
        tags: ["Food"],
        security: [
            ["X-AUTH-TOKEN" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "id", type: "integer"),
                    new OA\Property(property:"title", type:"string"),
                    new OA\Property(property:"description", type:"string"),
                    new OA\Property(property:"price", type:"integer"),
                    new OA\Property(property:"category_id", type:"integer"),
                    new OA\Property(property:"restaurant_id", type:"integer"),
                ]
                )
            ),
            responses: [
                new OA\Response(
                    response: 204,
                    description: "Plat modifié avec succès",
                    content: new OA\JsonContent(
                        type: "object",
                        properties: [
                            new OA\Property(property: "id", type: "integer"),
                            new OA\Property(property: "name", type:"string"),
                            new OA\Property(property: "description", type: "string"),
                            new OA\Property(property: "price", type: "integer"),
                            new OA\Property(property: "category_id", type: "integer"),
                            new OA\Property(property: "restaurant_id", type: "integer"),
                        ]
                    )
                ),
            ]
        )]


    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $food = $this->repository->find($id);

        if ($food) {
            $food = $this->serializer->deserialize($request->getContent(), Food::class, 'json', ['object_to_populate' => $food]);
        }

        $food->setUpdatedAt(new \DateTimeImmutable());

         $this->entityManager->flush();

        return new JsonResponse(
            null, 
            Response::HTTP_NO_CONTENT
        );

    }
    #[OA\Delete(
    path: "/api/food/{id}",
    summary: "Supprimer un plat",
    tags: ["Food"],
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            description: "ID du palt à supprimer",
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 204,
            description: "Plat supprimé avec succès"
        ),
        new OA\Response(
            response: 404,
            description: "Plat non trouvé"
        )
    ],
    security: [
        ["X-AUTH-TOKEN" => []]
    ]
)]
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $food = $this->repository->find($id);

        if ($food) {
            $this->entityManager->remove($food);
            $this->entityManager->flush();
            return new JsonResponse(
                null, 
                Response::HTTP_NO_CONTENT
            );
            
    }
        return new JsonResponse(
            null, 
            Response::HTTP_NOT_FOUND
        );
    }
}