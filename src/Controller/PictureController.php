<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Repository\PictureRepository;
use App\Entity\Restaurant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Attributes as OA;

#[Route('/api/picture', name: 'app_api_picture_')]
final class PictureController extends AbstractController
{
    public function __construct(
        private PictureRepository $repository,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer
    ) {}

    // -------------------------------
    //          CREATE (POST)
    // -------------------------------
    #[OA\Post(
        path: "/api/picture",
        summary: "Créer une nouvelle picture",
        tags: ["Picture"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "title", type: "string"),
                    new OA\Property(property: "restaurant", type: "integer"),
                    new OA\Property(property:"createdAt", type:"date-time"),

                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Picture created successfully",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property: "title", type: "string"),
                        new OA\Property(property: "createdAt", type: "date-time"),
                        new OA\Property(property: "restaurant", type: "integer"),
                    ]
                )
            )
        ]
    )]
    #[Route(methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $picture = $this->serializer->deserialize($request->getContent(), Picture::class, 'json');

        $picture->setCreatedAt(new \DateTimeImmutable());
        $picture->setUpdatedAt(new \DateTimeImmutable());

        // relation restaurant
        $data = json_decode($request->getContent(), true);
        if (!empty($data['restaurant'])) {
            $restaurant = $this->entityManager->getRepository(Restaurant::class)->find($data['restaurant']);
            $picture->setRestaurant($restaurant);
        }

        $this->entityManager->persist($picture);
        $this->entityManager->flush();

        $responseData = $this->serializer->serialize($picture, 'json');
        $location = $this->generateUrl('app_api_picture_show', ['id' => $picture->getId()]);

        return new JsonResponse($responseData, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    // -------------------------------
    //             READ
    // -------------------------------
    #[OA\Get(
        path: "/api/picture/{id}",
        summary: "Afficher une picture par ID",
        tags: ["Picture"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Picture retrieved successfully",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "title", type: "string"),
                        new OA\Property(property: "createdAt", type: "date-time"),
                        new OA\Property(property: "restaurant", type: "integer"),
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Picture not found")
        ]
    )]
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $picture = $this->repository->find($id);

        if ($picture) {
            $json = $this->serializer->serialize($picture, 'json');
            return new JsonResponse($json, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    // -------------------------------
    //            UPDATE (PUT)
    // -------------------------------
    #[OA\Put(
        path: "/api/picture/{id}",
        summary: "Modifier une picture",
        tags: ["Picture"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true)
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "title", type: "string"),
                    new OA\Property(property: "createdAt", type: "date-time"),
                    new OA\Property(property: "restaurant", type: "integer"),                ]
            )
        ),
        responses: [
            new OA\Response(response: 204, description: "Picture updated successfully"),
            new OA\Response(response: 404, description: "Picture not found")
        ]
    )]
    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $picture = $this->repository->find($id);
        if (!$picture) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $this->serializer->deserialize(
            $request->getContent(),
            Picture::class,
            'json',
            ['object_to_populate' => $picture]
        );

        $picture->setUpdatedAt(new \DateTimeImmutable());

        // relation restaurant
        $data = json_decode($request->getContent(), true);
        if (isset($data['restaurant'])) {
            $restaurant = $this->entityManager->getRepository(Restaurant::class)->find($data['restaurant']);
            $picture->setRestaurant($restaurant);
        }

        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    // -------------------------------
    //            DELETE
    // -------------------------------
    #[OA\Delete(
        path: "/api/picture/{id}",
        summary: "Supprimer une picture",
        tags: ["Picture"],
        responses: [
            new OA\Response(response: 204, description: "Deleted successfully"),
            new OA\Response(response: 404, description: "Picture not found")
        ]
    )]
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $picture = $this->repository->find($id);

        if ($picture) {
            $this->entityManager->remove($picture);
            $this->entityManager->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
