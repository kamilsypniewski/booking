<?php


namespace App\Services\Apartment;


use DateTime;

class Price
{
    protected const SEAL_NUMBER_OF_DAYS = 7;
    protected const SEAL_DISCOUNT = 10;

    private int $countDay;

    private int $finalPrice;

    /**
     * @param int $finalPrice
     * @return Price
     */
    private function setFinalPrice(int $finalPrice): Price
    {
        $this->finalPrice = $finalPrice;
        return $this;
    }

    /**
     * @return int
     */
    private function getFinalPrice(): int
    {
        return $this->finalPrice;
    }

    /**
     * @return int
     */
    private function getCountDay(): int
    {
        return $this->countDay;
    }

    private function setCountDay(DateTime $startDate, DateTime $endDate): self
    {
        $this->countDay = $endDate->diff($startDate)->days + 1;
        return $this;
    }

    /**
     * @return $this
     */
    private function sale(): self
    {
        if ($this->getCountDay() >= self::SEAL_NUMBER_OF_DAYS) {
            $this->setFinalPrice(
                $this->getFinalPrice() * (100 - self::SEAL_DISCOUNT) / 100
            );
        }

        return $this;
    }

    /**
     * @param int $price
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int $countBed
     * @return int
     */
    public function calculate(int $price, DateTime $startDate, DateTime $endDate, int $countBed): int
    {
        $this
            ->setCountDay($startDate, $endDate)
            ->setFinalPrice($this->getCountDay() * $price * $countBed)
            ->sale();

        return $this->getFinalPrice();
    }

}