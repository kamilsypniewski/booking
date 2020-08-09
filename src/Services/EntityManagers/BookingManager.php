<?php


namespace App\Services\EntityManagers;


use App\Entity\Bed;
use App\Entity\Booking;
use App\Model\Request\BookingNew;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookingManager extends AbstractManager
{
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
     * @param BookingNew $bookingNewData
     * @return Booking
     */
    public function handleNew(BookingNew $bookingNewData): Booking
    {
        $apartmentId = $bookingNewData->getApartment();

        $apartment = $this->getApartmentManager()->getApartment($bookingNewData->getApartment());

        $freeBeds = $this->getFreeBeds($apartment, $bookingNewData);

        $price = $this->getApartmentManager()->calculatePrice(
            $apartment->getPrice(),
            $bookingNewData->getStartDate(),
            $bookingNewData->getEndDate(),
            $bookingNewData->getCountBad()
        );

        return $this->new(
            $bookingNewData->getStartDate(),
            $bookingNewData->getEndDate(),
            array_slice($freeBeds, 0, $bookingNewData->getCountBad()),
            $price
        );
    }

    /**
     * @return ApartmentManager
     */
    public function getApartmentManager(): ApartmentManager
    {
        return $this->apartmentManager;
    }

    /**
     * @param $apartment
     * @param BookingNew $bookingNewData
     * @return mixed
     */
    private function getFreeBeds($apartment, BookingNew $bookingNewData)
    {
        $freeBeds = $this->getObjectManager()
            ->getRepository(Bed::class)
            ->findFreeRooms(
                $apartment->getId(),
                $bookingNewData->getStartDate()->format('Y-m-d H:i:s'),
                $bookingNewData->getEndDate()->format('Y-m-d H:i:s')
            );

        if (count($freeBeds) < $bookingNewData->getCountBad()) {
            throw new Exception('No vacancies on the given date', Response::HTTP_BAD_REQUEST);
        }

        return $freeBeds;
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param array $beds
     * @param int $price
     * @return Booking
     */
    private function new(DateTime $startDate, DateTime $endDate, array $beds, int $price): Booking
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
            throw new Exception('There is a problem with your booking details', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $errors = $this->getValidator()->validate($booking);
        if (count($errors) > 0) {
            throw new Exception('There was a problem validating the data while adding a new booking');
        }

        $this->getObjectManager()->persist($booking);
        $this->getObjectManager()->flush();

        return $booking;
    }

}