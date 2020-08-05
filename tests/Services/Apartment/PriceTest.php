<?php

namespace App\Tests\Services\Apartment;

use App\Services\Apartment\Price;
use DateTime;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{

    /**
     *
     */
    public function testCalculateOneDay(): void
    {
        $price = (new Price())->calculate(100,
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
        $price = (new Price())->calculate(100,
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
        $price = (new Price())->calculate(100,
            new DateTime('2020-01-01'),
            new DateTime('2020-01-07'),
            1
        );
        $this->assertSame($price, 630);
    }


}
