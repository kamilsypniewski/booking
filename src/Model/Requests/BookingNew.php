<?php

namespace App\Model\Request;

use DateTime;
use http\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\Response;


class BookingNew
{

    /**
     * @var DateTime
     */
    private DateTime $startDate;

    /**
     * @var DateTime
     */
    private DateTime  $endDate;

    /**
     * @var int
     */
    private int $countBad;

    /**
     * @var int
     */
    private int $apartment;

    /**
     * @param string $startDate
     * @return BookingNew
     */
    public function setStartDate(string $startDate): BookingNew
    {
        try {
            $this->startDate = new DateTime($startDate);
        } catch (\Exception $e) {
            throw new RuntimeException('Bad Request , incorrect data time ', Response::HTTP_BAD_REQUEST);
        }

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    /**
     * @param string $endDate
     * @return BookingNew
     */
    public function setEndDate(string $endDate): BookingNew
    {
        try {
            $this->endDate = new DateTime($endDate);
        } catch (\Exception $e) {
            throw new RuntimeException('Bad Request , incorrect data time ', Response::HTTP_BAD_REQUEST);
        }

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    /**
     * @param int $countBad
     * @return BookingNew
     */
    public function setCountBad(int $countBad): BookingNew
    {
        $this->countBad = $countBad;
        return $this;
    }

    /**
     * @return int
     */
    public function getCountBad(): int
    {
        return $this->countBad;
    }

    /**
     * @param int $apartment
     * @return BookingNew
     */
    public function setApartment(int $apartment): BookingNew
    {
        $this->apartment = $apartment;
        return $this;
    }

    /**
     * @return int
     */
    public function getApartment(): int
    {
        return $this->apartment;
    }

}