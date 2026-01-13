<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
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

#[Route('/api/category', name: 'app_api_category_')]
final class CategoryController extends AbstractController
{
    public function __construct(private CategoryRepository $repository, private EntityManagerInterface $entityManager, private SerializerInterface $serializer) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    #[OA\Post (
        path: "/api/category",
        summary: "Créer une nouvelle catégorie",
        tags: ["Category"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "title", type: "string"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Catégorie créée avec succès",
    
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property: "title", type: "string"),
                    ]
                )
                )
        ]
    )]

    #[Route(methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $category = $this->serializer->deserialize($request->getContent(), Category::class, 'json');
        $category->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        $responseData = $this->serializer->serialize($category, 'json');

        $location = $this->generateUrl('app_api_category_show', ['id' => $category->getId()]);
        
        return new JsonResponse($responseData, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    #[OA\Get (
        path: "/api/category/{id}",
        summary: "Afficher la catégorie par ID",
        tags: ["Category"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "id", type: "integer"),
                    new OA\Property(property: "title", type: "string"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Catégorie récupérée avec succès",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property:"title", type: "string"),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Catégorie non trouvée",
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
        $category = $this->repository->find($id);

        if ($category) {
            $responseData = $this->serializer->serialize($category, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }
        
        return new JsonResponse(
            null, 
            Response::HTTP_NOT_FOUND
        );
     
    }

#[OA\Put(
        path: "/api/category/{id}",
        summary: "Modifier une catégorie",
        tags: ["Category"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "id", type: "integer"),
                    new OA\Property(property:"title", type:"string"),
                ]
                )
            ),
            responses: [
                new OA\Response(
                    response: 204,
                    description: "Catégorie modifiée avec succès",
                    content: new OA\JsonContent(
                        type: "object",
                        properties: [
                            new OA\Property(property: "id", type: "integer"),
                            new OA\Property(property: "title", type:"string"),
                        ]
                    )
                ),
            ]
        )]

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $category = $this->repository->find($id);

        if ($category) {
            $category = $this->serializer->deserialize($request->getContent(), Category::class, 'json', ['object_to_populate' => $category]);
        }

        $category->setUpdatedAt(new \DateTimeImmutable());

         $this->entityManager->flush();

        return new JsonResponse(
            null, 
            Response::HTTP_NO_CONTENT
        );

    }

#[OA\Delete(
    path: "/api/category/{id}",
    summary: "Supprimer une catégorie",
    tags: ["Category"],
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            description: "ID de la catégorie à supprimer",
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 204,
            description: "categorie supprimée avec succès"
        ),
        new OA\Response(
            response: 404,
            description: "categorie non trouvé"
        )
    ],

)]
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $category = $this->repository->find($id);

        if ($category) {
            $this->entityManager->remove($category);
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