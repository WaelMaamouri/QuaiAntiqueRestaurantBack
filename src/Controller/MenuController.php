<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Schema;
use OpenApi\Attributes\Items;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/menu', name: 'api_app_menu')]

final class MenuController extends AbstractController
{
    public function __construct(private MenuRepository $repository, private EntityManagerInterface $entityManager, private SerializerInterface $serializer) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    #[OA\Post (
        path: "/api/menu",
        summary: "Créer un nouveau menu",
        tags: ["Menu"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "title", type: "string"),
                    new OA\Property(property: "description", type: "text"),
                    new OA\Property(property: "price", type: "integer"),
                    new OA\Property(property: "createdAt", type: "date-time"),
                    new OA\Property(property: "restaurant_id", type: "integer"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Menu created successfully",
    
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property: "title", type: "string"),
                        new OA\Property(property: "description", type:"string"),
                        new OA\Property(property: "price", type: "integer"),
                        new OA\Property(property: "createdAt", type: "date-time"),
                        new OA\Property(property: "restaurant_id", type: "integer"),
                    ]
                )
                )
        ]
    )]

    #[Route(methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $menu = $this->serializer->deserialize($request->getContent(), Menu::class, 'json');
        $menu->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($menu);
        $this->entityManager->flush();

        $responseData = $this->serializer->serialize($menu, 'json');

        $location = $this->generateUrl('app_api_menu_show', ['id' => $menu->getId()]);
        
        return new JsonResponse($responseData, Response::HTTP_CREATED, ['Location' => $location], true);
    }
    #[OA\Get (
        path: "/api/menu/{id}",
        summary: "Afficher le menu par ID",
        tags: ["Menu"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "id", type: "integer"),
                    new OA\Property(property: "title", type: "string"),
                    new OA\Property(property: "description", type: "string"),
                    new OA\Property(property: "price", type: "integer"),
                    new OA\Property(property: "createdAt", type: "date-time"),
                    new OA\Property(property: "restaurant_id", type: "integer"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Menu retrieved successfully",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property:"title", type: "string"),
                        new OA\Property(property:"description", type: "string"),
                        new OA\Property(property:"price", type: "integer"),
                        new OA\Property(property:"createdAt", type: "date-time"),
                        new OA\Property(property:"restaurant_id", type: "integer"),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Menu not found",
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
        $menu = $this->repository->find($id);
        if ($menu) {
            $responseData = $this->serializer->serialize($menu, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }
        
        return new JsonResponse(
            null, 
            Response::HTTP_NOT_FOUND
        );
     
    }

#[OA\Put(
        path: "/api/menu/{id}",
        summary: "Modifier un menu",
        tags: ["Menu"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "id", type: "integer"),
                    new OA\Property(property:"title", type:"string"),
                    new OA\Property(property:"description", type:"string"),
                    new OA\Property(property:"price", type:"integer"),
                    new OA\Property(property:"createdAt", type:"date-time"),
                    new OA\Property(property:"restaurant_id", type:"integer"),
                ]
                )
            ),
            responses: [
                new OA\Response(
                    response: 204,
                    description: "menu updated successfully",
                    content: new OA\JsonContent(
                        type: "object",
                        properties: [
                            new OA\Property(property: "id", type: "integer"),
                            new OA\Property(property: "title", type:"string"),
                            new OA\Property(property: "description", type: "string"),
                            new OA\Property(property:"price", type:"integer"),
                            new OA\Property(property:"createdAt", type:"date-time"),
                            new OA\Property(property:"restaurant_id", type:"integer"),
                        ]
                    )
                ),
            ]
        )]

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $menu = $this->repository->find($id);

        if ($menu) {
            $menu = $this->serializer->deserialize($request->getContent(), Menu::class, 'json', ['object_to_populate' => $menu]);
        }
        
        $menu->setUpdatedAt(new \DateTimeImmutable());
        
         $this->entityManager->flush();

        return new JsonResponse(
            null, 
            Response::HTTP_NO_CONTENT
        );

    }

#[OA\Delete(
    path: "/api/menu/{id}",
    summary: "Supprimer un menu",
    tags: ["Menu"],
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            description: "ID du menu à supprimer",
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 204,
            description: "menu supprimé avec succès"
        ),
        new OA\Response(
            response: 404,
            description: "menu non trouvé"
        )
    ],

)]

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $menu = $this->repository->find($id);

        if ($menu) {
            $this->entityManager->remove($menu);
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
