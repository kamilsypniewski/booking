<?php


namespace App\Services\EntityManagers;


use App\Entity\Apartment;
use App\Services\Apartment\Price;

use DateTime;

use Doctrine\ORM\EntityManagerInterface;
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
     * @param int $id
     * @return Apartment
     */
    public function getById(int $id): object
    {
        /** @var Apartment $apartment */
        return $this->getObjectManager()
            ->getRepository(Apartment::class)
            ->find($id);
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

}