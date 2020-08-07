<?php


namespace App\Services\EntityManagers;


use App\Entity\Apartment;
use App\Entity\Bed;
use App\Entity\Booking;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\BadMethodCallException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookingManager extends AbstractManager
{
    public const ACTION_NEW = 'new';

    public const ENTITY_NAME_START_DATE = 'start_date';
    public const ENTITY_NAME_END_DATE = 'end_date';
    public const ENTITY_NAME_COUNT_BAD = 'count_bad';
    public const ENTITY_NAME_APARTMENT = 'apartment';

    /**
     * @var ApartmentManager
     */
    private ApartmentManager $apartmentManager;

    public function __construct(EntityManagerInterface $objectManager, ValidatorInterface $validator, ApartmentManager $price)
    {
        parent::__construct($objectManager, $validator);
        $this->apartmentManager = $price;
    }


    /**
     * @return ApartmentManager
     */
    public function getApartmentManager(): ApartmentManager
    {
        return $this->apartmentManager;
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param array $beds
     * @param int $price
     * @return Booking
     */
    protected function new(DateTime $startDate, DateTime $endDate, array $beds, int $price): Booking
    {
        try {
            $booking = (new Booking())
                ->setCreatedAt(new DateTime())
                ->setStartDate($startDate)
                ->setEndDate($endDate)
                ->setPrice($price);

            foreach ($beds as $bed) {
                $booking->addBed($bed);
            }

        } catch (\Throwable|Exception $e) {
            throw new Exception('There is a problem with your booking details', Response::HTTP_INTERNAL_SERVER_ERROR );
        }

        $errors = $this->getValidator()->validate($booking);
        if (count($errors) > 0) {
            throw new Exception('There was a problem validating the data while adding a new booking');
        }

        $this->getObjectManager()->persist($booking);
        $this->getObjectManager()->flush();

        return $booking;
    }

    /**
     * @param array $data
     * @return Booking
     */
    public function handleNew(array $data): Booking
    {
        try {
            $startDate = new DateTime($data[self::ENTITY_NAME_START_DATE]);
            $endDate = new DateTime($data[self::ENTITY_NAME_END_DATE]);
        } catch (\Exception $e) {
            throw new Exception('Bad Request , incorrect data time ', Response::HTTP_BAD_REQUEST);
        }

        $apartment = $this->getApartmentManager()->getById($data[self::ENTITY_NAME_APARTMENT]);

        $freeBeds = $this->getObjectManager()
            ->getRepository(Bed::class)
            ->findFreeRooms($apartment->getId(), $startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s'));

        if(count($freeBeds) < $data[self::ENTITY_NAME_COUNT_BAD]) {
            throw new Exception('No vacancies on the given date', Response::HTTP_BAD_REQUEST);
        }


        $price = $this->getApartmentManager()->calculatePrice(
            $apartment->getPrice(),
            $startDate,
            $endDate,
            $data[self::ENTITY_NAME_COUNT_BAD]
        );

        return $this->new(
            $startDate,
            $endDate,
            array_slice($freeBeds, 0, $data[self::ENTITY_NAME_COUNT_BAD]),
            $price
        );
    }

}