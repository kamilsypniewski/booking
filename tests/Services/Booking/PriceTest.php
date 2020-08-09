<?php

namespace App\Tests\Services\Booking;

use App\Services\Booking\Price;
use App\Services\Booking\Promotion;
use DateTime;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{

    /**
     *
     */
    public function testCalculateOneDay(): void
    {
        $price = (new Price(new Promotion()))->calculate(
            100,
            new DateTime('2020-01-01'),
            new DateTime('2020-01-01'),
            1
        );
        $this->assertSame($price, 100);
    }

    /**
     *
     */
    public function testCalculateTwoDay(): void
    {
        $price = (new Price(new Promotion()))->calculate(
            100,
            new DateTime('2020-01-01'),
            new DateTime('2020-01-02'),
            1
        );
        $this->assertSame($price, 200);
    }

    /**
     *
     */
    public function testCalculateSevenDay(): void
    {
        $price = (new Price(new Promotion()))->calculate(
            100,
            new DateTime('2020-01-01'),
            new DateTime('2020-01-07'),
            1
        );
        $this->assertSame($price, 630);
    }


}
