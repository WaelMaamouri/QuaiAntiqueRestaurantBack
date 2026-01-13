<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Repository\BookingRepository;
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

#[Route('/api/booking', name: 'app_api_booking_')]
final class BookingController extends AbstractController
{
    public function __construct(private BookingRepository $repository, private EntityManagerInterface $entityManager, private SerializerInterface $serializer) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;

    }

    #[OA\Post (
        path: "/api/booking",
        summary: "Créer une nouvelle réservation",
        tags: ["Booking"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "guestNumber", type: "integer"),
                    new OA\Property(property: "orderDate", type: "date"),
                    new OA\Property(property: "orderHour", type: "date-time"),
                    new OA\Property(property: "allergy", type: "text"),
                    new OA\Property(property: "createdAt", type: "date-time"),
                    new OA\Property(property: "restaurant", type: "integer"),
                    new OA\Property(property: "client", type: "integer"),
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
                    new OA\Property(property: "guestNumber", type: "integer"),
                    new OA\Property(property: "orderDate", type: "date"),
                    new OA\Property(property: "orderHour", type: "date-time"),
                    new OA\Property(property: "allergy", type: "text"),
                    new OA\Property(property: "createdAt", type: "date-time"),
                    new OA\Property(property: "restaurant", type: "integer"),
                    new OA\Property(property: "client", type: "integer"),
                    ]
                )
                )
        ]
    )]
    
    #[Route(methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $booking = $this->serializer->deserialize($request->getContent(), Booking::class, 'json');
        $booking->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($booking);
        $this->entityManager->flush();

        $responseData = $this->serializer->serialize($booking, 'json');

        $location = $this->generateUrl('app_api_restaurant_show', ['id' => $booking->getId()]);
        
        return new JsonResponse($responseData, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    #[OA\Get(
        path: "/api/booking/{id}",
        summary: "Afficher une réservation par ID",
        tags: ["Booking"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "guestNumber", type: "integer"),
                    new OA\Property(property: "orderDate", type: "date"),
                    new OA\Property(property: "orderHour", type: "date-time"),
                    new OA\Property(property: "allergy", type: "text"),
                    new OA\Property(property: "createdAt", type: "date-time"),
                    new OA\Property(property: "restaurant", type: "integer"),
                    new OA\Property(property: "client", type: "integer"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Reservation retrieved successfully",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                    new OA\Property(property: "guestNumber", type: "integer"),
                    new OA\Property(property: "orderDate", type: "date"),
                    new OA\Property(property: "orderHour", type: "date-time"),
                    new OA\Property(property: "allergy", type: "text"),
                    new OA\Property(property: "createdAt", type: "date-time"),
                    new OA\Property(property: "restaurant", type: "integer"),
                    new OA\Property(property: "client", type: "integer"),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Reservation not found",
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
        $booking = $this->repository->find($id);
        if ($booking) {
            $responseData = $this->serializer->serialize($booking, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }
        
        return new JsonResponse(
            null, 
            Response::HTTP_NOT_FOUND
        );
     
    }
#[OA\Put(
        path: "/api/booking/{id}",
        summary: "Modifier une réservation",
        tags: ["Booking"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "guestNumber", type: "integer"),
                    new OA\Property(property: "orderDate", type: "date"),
                    new OA\Property(property: "orderHour", type: "date-time"),
                    new OA\Property(property: "allergy", type: "text"),
                    new OA\Property(property: "createdAt", type: "date-time"),
                    new OA\Property(property: "restaurant", type: "integer"),
                    new OA\Property(property: "client", type: "integer"),                ]
                )
            ),
            responses: [
                new OA\Response(
                    response: 204,
                    description: "Restaurant updated successfully",
                    content: new OA\JsonContent(
                        type: "object",
                        properties: [
                    new OA\Property(property: "guestNumber", type: "integer"),
                    new OA\Property(property: "orderDate", type: "date"),
                    new OA\Property(property: "orderHour", type: "date-time"),
                    new OA\Property(property: "allergy", type: "text"),
                    new OA\Property(property: "createdAt", type: "date-time"),
                    new OA\Property(property: "updatedAt", type: "date-time"),
                    new OA\Property(property: "restaurant", type: "integer"),
                    new OA\Property(property: "client", type: "integer"),                        
                    ]
                    )
                ),
            ]
        )]

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $booking = $this->repository->find($id);

        if ($booking) {
            $booking = $this->serializer->deserialize($request->getContent(), Booking::class, 'json', ['object_to_populate' => $booking]);
        }
        
        $booking->setUpdatedAt(new \DateTimeImmutable());
        
         $this->entityManager->flush();

        return new JsonResponse(
            null, 
            Response::HTTP_NO_CONTENT
        );

    }

#[OA\Delete(
    path: "/api/booking/{id}",
    summary: "Supprimer une réservation",
    tags: ["Booking"],
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            description: "ID de la réservation à supprimer",
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 204,
            description: "Réservation supprimé avec succès"
        ),
        new OA\Response(
            response: 404,
            description: "Réservation non trouvé"
        )
    ],

)]

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $booking = $this->repository->find($id);

        if ($booking) {
            $this->entityManager->remove($booking);
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