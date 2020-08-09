<?php

namespace App\Repository;

use App\Entity\Bed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bed|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bed|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bed[]    findAll()
 * @method Bed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bed::class);
    }

    /**
     * @param int $apartmentId
     * @param string $startDate
     * @param string $endDate
     * @return int|mixed|string
     */
    public function findFreeRooms(int $apartmentId,string $startDate, string $endDate)
    {
        $query =  $this->createQueryBuilder('bed')
            ->leftjoin('bed.apartment', 'apartment')
            ->leftjoin('bed.bookings', 'bookings')
            ->andWhere('apartment.id = :apartmentId')
            ->andWhere('(
                (not (
                    :startDate BETWEEN bookings.startDate AND bookings.endDate
                    OR
                    :endDate BETWEEN bookings.startDate AND bookings.endDate
                    ) 
                AND
                not (
                    bookings.startDate BETWEEN :startDate AND :endDate
                    OR
                    bookings.endDate BETWEEN :startDate AND :endDate
                    )
                )
                or bookings.endDate is null
                or bookings.startDate is null            
            )')
            ->setParameters([
                    'apartmentId' => $apartmentId,
                    'startDate' => $startDate,
                    'endDate' => $endDate
                ]
            );

        return $query->getQuery()
            ->getResult();
    }
}
