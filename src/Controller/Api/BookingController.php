<?php


namespace App\Controller\Api;

use App\Model\Request\BookingNew;

use App\Services\EntityManagers\BookingManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use http\Exception\InvalidArgumentException;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Rest\Route("/api/booking")
 */
class BookingController extends AbstractFOSRestController
{

    /**
     * Book a bed for a given apartment.
     *
     * @SWG\Parameter(
     *      name="body",
     *      in="body",
     *      @SWG\Schema(
     *          type="string",
     *          ref=@Model(type=BookingNew::class)
     *      )
     * )
     *
     * @SWG\Response(
     *     response=201,
     *     description="Correct execution of the booking",
     *     @SWG\Schema(
     *          @SWG\Property(
     *              property="id",
     *              type="integer",
     *              example="123",
     *              description="Booking id"
     *          ),
     *          @SWG\Property(
     *              property="Beds",
     *              title="Beds",
     *              type="array",
     *              description="List of reserved beds",
     *                  @SWG\Items(
     *                          type="string",
     *                          example="417ab64f-e45b-35b8-a9cb-ad615d16d680"
     *                  )
     *          ),
     *          @SWG\Property(
     *              property="price",
     *              type="integer",
     *              example="1000",
     *              description="Rental price"
     *          )
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request error",
     *     @SWG\Schema(
     *          @SWG\Property(
     *              property="error",
     *              type="string",
     *              example="Bad request, invalid json",
     *              description="Bad request error"
     *          ),
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not Found",
     *     @SWG\Schema(
     *          @SWG\Property(
     *              property="error",
     *              type="string",
     *              example="The given apartment has not been found",
     *              description="Not Found"
     *          ),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="Internal Server Error",
     *     @SWG\Schema(
     *          @SWG\Property(
     *              property="error",
     *              type="string",
     *              example="There is a problem with your booking details",
     *              description="Internal Server Error"
     *          ),
     *     )
     * )
     *
     * @Rest\Post("/new", name="api_booking_new")
     * @param Request $request
     * @param BookingManager $bookingManager
     * @return JsonResponse
     */
    public function new(Request $request, BookingManager $bookingManager): JsonResponse
    {
        try {
            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);
            /** @var BookingNew $bookingNewRequest */
            $bookingNewRequest = $serializer->deserialize($request->getContent(), BookingNew::class, 'json');
            if (!($bookingNewRequest instanceof BookingNew)) {
                throw new InvalidArgumentException('Bad request, invalid json', Response::HTTP_BAD_REQUEST);
            }

            $booking = $bookingManager->handleNew($bookingNewRequest);

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