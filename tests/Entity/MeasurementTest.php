<?php

namespace App\Tests\Entity;
use App\Entity\Measurement;
use PHPUnit\Framework\TestCase;

class MeasurementTest extends TestCase
{


    public function dataGetFahrenheit(): array
    {
        return [
            [0, 32],
            [-100, -148],
            [50, 122],
            [-25, -13],
            [12.5, 54.5],
            [30.3, 86.54],
            [-10.1, 13.82],
            [3.3, 37.94],
            [-50.5, -58.9],
            [75.8, 168.44],
        ];
    }

    /**
     * @dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit($celsius, $expectedFahrenheit): void
    {

//        $measurement = new Measurement();
//        $measurement->setCelsius(0);
//        $this->assertEquals(32, $measurement->getFahrenheit());
//        $measurement->setCelsius(-100);
//        $this->assertEquals(-148, $measurement->getFahrenheit());
//        $measurement->setCelsius(100);
//        $this->assertEquals(212, $measurement->getFahrenheit());

        $measurement = new Measurement();
        $measurement->setCelsius($celsius);


        $this->assertEquals(round($expectedFahrenheit, 2), round($measurement->getFahrenheit(), 2));
    }
}
