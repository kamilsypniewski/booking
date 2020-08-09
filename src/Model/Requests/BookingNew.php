<?php

namespace App\Model\Request;

use DateTime;
use Exception;
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
    private DateTime $endDate;

    /**
     * @var int
     */
    private int $countBad;

    /**
     * @var int
     */
    private int $apartment;

    /**
     * @return DateTime
     */
    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    /**
     * @param string $startDate
     * @return BookingNew
     * @throws Exception
     */
    public function setStartDate(string $startDate): BookingNew
    {
        try {
            $this->startDate = new DateTime($startDate);
        } catch (\Exception $e) {
            throw new Exception('Bad Request, incorrect start data time ', Response::HTTP_BAD_REQUEST);
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
     * @param string $endDate
     * @return BookingNew
     * @throws Exception
     */
    public function setEndDate(string $endDate): BookingNew
    {
        try {
            $endDate = new DateTime($endDate);

            if ($this->getStartDate() > $endDate) {
                throw new Exception('Bad Request, the end date cannot be less than the start date ', Response::HTTP_BAD_REQUEST);
            }

            $this->endDate = $endDate;
        } catch (\Exception $e) {
            throw new Exception('Bad Request, incorrect end data time ', Response::HTTP_BAD_REQUEST);
        }

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
     * @param int $countBad
     * @return BookingNew
     * @throws Exception
     */
    public function setCountBad(int $countBad): BookingNew
    {
        if (0 > $countBad) {
            throw new Exception('Bad Request , incorrect count bad ', Response::HTTP_BAD_REQUEST);
        }
        $this->countBad = $countBad;
        return $this;
    }

    /**
     * @return int
     */
    public function getApartment(): int
    {
        return $this->apartment;
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

}