<?php


namespace App\Services\Booking;


use DateTime;

class Price
{
    /**
     * @var int
     */
    private int $countDay;

    /**
     * @var int
     */
    private int $finalPrice;
    /**
     * @var Promotion
     */
    private Promotion $promotion;

    public function __construct(Promotion $promotion)
    {
        $this->promotion = $promotion;
    }

    /**
     * @param int $finalPrice
     * @return Price
     */
    public function setFinalPrice(int $finalPrice): Price
    {
        $this->finalPrice = $finalPrice;
        return $this;
    }

    /**
     * @return int
     */
    public function getFinalPrice(): int
    {
        return $this->finalPrice;
    }

    /**
     * @return int
     */
    public function getCountDay(): int
    {
        return $this->countDay;
    }

    public function setCountDay(DateTime $startDate, DateTime $endDate): self
    {
        $this->countDay = $endDate->diff($startDate)->days + 1;
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
        $this->setCountDay($startDate, $endDate)
            ->setFinalPrice($this->getCountDay() * $price * $countBed)
            ->promotion();

        return $this->getFinalPrice();
    }

    /**
     * @return $this
     */
    private function promotion(): self
    {
        $this->getPromotion()->longLease($this);

        return $this;
    }

    /**
     * @return Promotion
     */
    public function getPromotion(): Promotion
    {
        return $this->promotion;
    }

}