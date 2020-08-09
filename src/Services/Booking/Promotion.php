<?php


namespace App\Services\Booking;


class Promotion
{
    protected const PROMOTION_NUMBER_OF_DAYS = 7;
    protected const PROMOTION_DISCOUNT = 10;

    public function longLease($price): void
    {
        if ($price->getCountDay() >= self::PROMOTION_NUMBER_OF_DAYS) {
            $price->setFinalPrice(
                $price->getFinalPrice() * (100 - self::PROMOTION_DISCOUNT) / 100
            );
        }
    }
}