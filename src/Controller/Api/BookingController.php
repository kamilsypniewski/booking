<?php


namespace App\Controller\Api;


use App\Services\EntityManagers\BookingManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use http\Exception\InvalidArgumentException;

use JsonException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Rest\Route("/api/booking")
 */
class BookingController extends AbstractFOSRestController
{

    /**
     * @Rest\Post("/new", name="api_booking_new")
     *
     * @param Request $request
     * @param BookingManager $bookingManager
     * @return JsonResponse
     */
    public function new(Request $request, BookingManager $bookingManager): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new InvalidArgumentException('Bad request, invalid json', Response::HTTP_BAD_REQUEST);
        }

        try {
            $booking = $bookingManager->handleNew($data);

            $bedsName = [];
            foreach ($booking->getBed() as $bed) {
                $bedsName[] = $bed->getName();
            }

            return new JsonResponse(
                [
                    'id' => $booking->getId(),
                    'beds' => $bedsName,
                    'price' => $booking->getPrice()
                ],
                Response::HTTP_CREATED
            );
        } catch (Exception $e) {
            return new JsonResponse(
                ['message' => $e->getMessage()],
                $e->getCode()
            );
        }
    }

}