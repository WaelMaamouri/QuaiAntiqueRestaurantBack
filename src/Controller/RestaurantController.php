<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
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

#[Route('/api/restaurant', name: 'app_api_restaurant_')]
final class RestaurantController extends AbstractController
{
    public function __construct(private RestaurantRepository $repository, private EntityManagerInterface $entityManager, private SerializerInterface $serializer) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;

    }

    #[OA\Post (
        path: "/api/restaurant",
        summary: "Créer un nouveau restaurant",
        tags: ["Restaurant"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "name", type: "string"),
                    new OA\Property(property: "description", type: "text"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Restaurant created successfully",
    
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property: "name", type: "string"),
                        new OA\Property(property: "description", type:"string"),
                    ]
                )
                )
        ]
    )]
    
    #[Route(methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $restaurant = $this->serializer->deserialize($request->getContent(), Restaurant::class, 'json');
        $restaurant->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($restaurant);
        $this->entityManager->flush();

        $responseData = $this->serializer->serialize($restaurant, 'json');

        $location = $this->generateUrl('app_api_restaurant_show', ['id' => $restaurant->getId()]);
        
        return new JsonResponse($responseData, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    #[OA\Get (
        path: "/api/restaurant/{id}",
        summary: "Afficher le restaurant par ID",
        tags: ["Restaurant"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "id", type: "integer"),
                    new OA\Property(property: "name", type: "string"),
                    new OA\Property(property: "description", type: "string"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Restaurant retrieved successfully",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property:"name", type: "string"),
                        new OA\Property(property:"description", type: "string"),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Restaurant not found",
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
       // Chercer le restaurant id = 1
        $restaurant = $this->repository->find($id);
        if ($restaurant) {
            $responseData = $this->serializer->serialize($restaurant, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }
        
        return new JsonResponse(
            null, 
            Response::HTTP_NOT_FOUND
        );
     
    }
    #[OA\Put(
        path: "/api/restaurant/{id}",
        summary: "Modifier un restaurant",
        tags: ["Restaurant"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "id", type: "integer"),
                    new OA\Property(property:"name", type:"string"),
                    new OA\Property(property:"description", type:"string"),
                ]
                )
            ),
            responses: [
                new OA\Response(
                    response: 204,
                    description: "Restaurant updated successfully",
                    content: new OA\JsonContent(
                        type: "object",
                        properties: [
                            new OA\Property(property: "id", type: "integer"),
                            new OA\Property(property: "name", type:"string"),
                            new OA\Property(property: "description", type: "string"),
                        ]
                    )
                ),
            ]
        )]

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $restaurant = $this->repository->find($id);

        if ($restaurant) {
            $restaurant = $this->serializer->deserialize($request->getContent(), Restaurant::class, 'json', ['object_to_populate' => $restaurant]);
        }
        
        $restaurant->setUpdatedAt(new \DateTimeImmutable());
        
         $this->entityManager->flush();

        return new JsonResponse(
            null, 
            Response::HTTP_NO_CONTENT
        );

    }

    #[OA\Delete(
        path: "/api/restaurant/{id}",
        summary: "Supprimer un restaurant",
        tags: ["Restaurant"],
        parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            description: "ID du restaurant à supprimer",
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 204,
            description: "restaurant supprimé avec succès"
        ),
        new OA\Response(
            response: 404,
            description: "restaurant non trouvé"
        )
    ],

)]

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $restaurant = $this->repository->find($id);

        if ($restaurant) {
            $this->entityManager->remove($restaurant);
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