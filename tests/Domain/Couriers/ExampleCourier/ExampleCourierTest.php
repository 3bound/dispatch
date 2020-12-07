<?php

namespace Tests\Domain\Couriers;

use PHPUnit\Framework\TestCase;
use Dispatch\Domain\Couriers\ExampleCourier\ExampleCourier;

final class ExampleCourierTest extends TestCase
{
    /**
     * Can the class be instantiated?
     *
     */
    public function testCanBeInstantiated(): void
    {
        $this->assertInstanceOf(
            ExampleCourier::class,
            new ExampleCourier()
        );
    }

    /**
     * Does the courier generate string consignment IDs?
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testGenerateIdOfTypeString(): void
    {
        $courier = new ExampleCourier();
        $result = $courier->generateConsignmentId();
        $this->assertIsString($result);
    }

    /**
     * Does the courier generate non-empty consignment IDs?
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testGenerateNonEmptyId(): void
    {
        $courier = new ExampleCourier();
        $result = $courier->generateConsignmentId();
        $this->assertNotEmpty($result);
    }

    /**
     * Does the courier generate unique consignment IDs?
     *
     * @depends testGenerateIdOfTypeString
     * @depends testGenerateNonEmptyId
     *
     */
    public function testGenerateUniqueIds(): void
    {
        $courier = new ExampleCourier();
        $results = [];
        $numSamples = 1000;

        for ($i = 0; $i < $numSamples; $i++) {
            $results[] = $courier->generateConsignmentId();
        }

        $uniqueResults = array_unique($results);
        $this->assertEquals(count($uniqueResults), count($results));
    }

    /**
     * Is the courier name non-empty?
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testNameNotEmpty(): void
    {
        $courier = new ExampleCourier();
        $result = $courier->getName();
        $this->assertNotEmpty($result);
    }

    /**
     * Is the courier name a string?
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testNameIsString(): void
    {
        $courier = new ExampleCourier();
        $result = $courier->getName();
        $this->assertIsString($result);
    }
}
