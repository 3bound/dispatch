<?php

namespace Tests\Domain;

use PHPUnit\Framework\TestCase;
use Dispatch\Domain\Consignment\Consignment;
use Dispatch\Domain\Couriers\ExampleCourier\ExampleCourier;

final class ConsignmentTest extends TestCase
{
    /**
     * Can the class be instantiated?
     *
     */
    public function testCanBeInstantiated(): void
    {
        $courier = $this->getMockBuilder(ExampleCourier::class)
                        ->setMethods(['generateConsignmentId'])
                        ->setMethods(['getName'])
                        ->getMock();

        $courier->method('generateConsignmentId')->willReturn('A12345');
        $courier->method('getName')->willReturn('Example');

        $this->assertInstanceOf(
            Consignment::class,
            new Consignment($courier)
        );
    }


    /**
     * Instantiation fails when the courier generates an empty consignment ID
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testCannotInstantiateWithEmptyConsignmentId(): void
    {
        $courier = $this->getMockBuilder(ExampleCourier::class)
                        ->setMethods(['generateConsignmentId'])
                        ->setMethods(['getName'])
                        ->getMock();

        $courier->method('generateConsignmentId')->willReturn('');
        $courier->method('getName')->willReturn('Example');

        $this->expectException(\InvalidArgumentException::class);
        new Consignment($courier);
    }


    /**
     * Instantiation fails when the courier does not provide a name
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testCannotInstantiateWithEmptyCourierName(): void
    {
        $courier = $this->getMockBuilder(ExampleCourier::class)
                        ->setMethods(['generateConsignmentId'])
                        ->setMethods(['getName'])
                        ->getMock();

        $courier->method('generateConsignmentId')->willReturn('A12345');
        $courier->method('getName')->willReturn('');

        $this->expectException(\InvalidArgumentException::class);
        new Consignment($courier);
    }


    /**
     * Get the consignment ID
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testGetConsignmentId(): void
    {
        $courier = $this->getMockBuilder(ExampleCourier::class)
                        ->setMethods(['generateConsignmentId'])
                        ->getMock();

        $expected = 'A12345';
        $courier->method('generateConsignmentId')->willReturn($expected);
        $consignment = new Consignment($courier);
        $result = $consignment->getConsignmentId();
        $this->assertEquals($expected, $result);
    }


    /**
     * Get the courier name
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testGetCourierName(): void
    {
        $courier = $this->getMockBuilder(ExampleCourier::class)
                        ->setMethods(['getName'])
                        ->getMock();

        $expected = 'Example';
        $courier->method('getName')->willReturn($expected);
        $consignment = new Consignment($courier);
        $result = $consignment->getCourierName();
        $this->assertEquals($expected, $result);
    }


    /**
     * Get the courier
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testGetCourier(): void
    {
        $courier = $this->getMockBuilder(ExampleCourier::class)
                        ->setMethods(['getName'])
                        ->getMock();

        $courier->method('getName')->willReturn('Example');

        $consignment = new Consignment($courier);
        $result = $consignment->getCourier();
        $this->assertEquals($courier, $result);
    }
}
