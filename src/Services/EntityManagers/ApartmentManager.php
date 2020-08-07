<?php


namespace App\Services\EntityManagers;


use App\Entity\Apartment;
use App\Services\Apartment\Price;

use DateTime;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApartmentManager extends AbstractManager
{

    /**
     * @var Price
     */
    private Price $price;

    /**
     * ApartmentManager constructor.
     * @param EntityManagerInterface $objectManager
     * @param ValidatorInterface $validator
     * @param Price $price
     */
    public function __construct(EntityManagerInterface $objectManager, ValidatorInterface $validator, Price $price)
    {
        parent::__construct($objectManager, $validator);
        $this->price = $price;
    }

    /**
     * @return Price
     */
    public function getPrice(): Price
    {
        return $this->price;
    }

    /**
     * @param int $price
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int $countBad
     * @return int
     */
    public function calculatePrice(int $price, DateTime $startDate, DateTime $endDate, int $countBad): int
    {
        return $this->getPrice()->calculate($price, $startDate, $endDate, $countBad);
    }

    /**
     * @param int $apartmentId
     * @return Apartment|object
     */
    public function getApartment(int $apartmentId): Apartment
    {
        $apartment = $this->getById($apartmentId);
        if($apartment instanceof Apartment) {
            return $apartment;
        }
        throw new Exception('The given apartment has not been found', Response::HTTP_NOT_FOUND);
    }

    /**
     * @param int $id
     * @return Apartment
     */
    private function getById(int $id): object
    {
        /** @var Apartment $apartment */
        return $this->getObjectManager()
            ->getRepository(Apartment::class)
            ->find($id);
    }
}