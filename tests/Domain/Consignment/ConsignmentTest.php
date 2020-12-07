<?php

namespace Tests\Domain;

use PHPUnit\Framework\TestCase;
use Dispatch\Domain\Consignment\Consignment;
use Dispatch\Domain\Order\Order;
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

        $order = $this->getMockBuilder(Order::class)
                        ->setMethods(['getId'])
                        ->disableOriginalConstructor()
                        ->getMock();

        $order->method('getId')->willReturn('AA01');

        $this->assertInstanceOf(
            Consignment::class,
            new Consignment($order, $courier)
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
        $this->expectException(\InvalidArgumentException::class);

        $courier = $this->getMockBuilder(ExampleCourier::class)
                        ->setMethods(['generateConsignmentId'])
                        ->setMethods(['getName'])
                        ->getMock();

        $courier->method('generateConsignmentId')->willReturn('');
        $courier->method('getName')->willReturn('Example');

        $order = $this->getMockBuilder(Order::class)
                        ->setMethods(['getId'])
                        ->disableOriginalConstructor()
                        ->getMock();

        $order->method('getId')->willReturn('AA01');

        new Consignment($order, $courier);
    }


    /**
     * Instantiation fails when the courier does not provide a name
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testCannotInstantiateWithEmptyCourierName(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $courier = $this->getMockBuilder(ExampleCourier::class)
                        ->setMethods(['generateConsignmentId'])
                        ->setMethods(['getName'])
                        ->getMock();

        $courier->method('generateConsignmentId')->willReturn('A12345');
        $courier->method('getName')->willReturn('');

        $order = $this->getMockBuilder(Order::class)
                        ->setMethods(['getId'])
                        ->disableOriginalConstructor()
                        ->getMock();

        $order->method('getId')->willReturn('AA01');

        new Consignment($order, $courier);
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

        $order = $this->getMockBuilder(Order::class)
                        ->setMethods(['getId'])
                        ->disableOriginalConstructor()
                        ->getMock();

        $order->method('getId')->willReturn('AA01');

        $expected = 'A12345';
        $courier->method('generateConsignmentId')->willReturn($expected);
        $consignment = new Consignment($order, $courier);
        $result = $consignment->getId();
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

        $order = $this->getMockBuilder(Order::class)
                        ->setMethods(['getId'])
                        ->disableOriginalConstructor()
                        ->getMock();

        $order->method('getId')->willReturn('AA01');

        $expected = 'Example';
        $courier->method('getName')->willReturn($expected);
        $consignment = new Consignment($order, $courier);
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

        $order = $this->getMockBuilder(Order::class)
                        ->setMethods(['getId'])
                        ->disableOriginalConstructor()
                        ->getMock();

        $order->method('getId')->willReturn('AA01');

        $consignment = new Consignment($order, $courier);
        $result = $consignment->getCourier();
        $this->assertEquals($courier, $result);
    }


    /**
     * Get the order
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testGetOrder(): void
    {
        $courier = $this->getMockBuilder(ExampleCourier::class)
                        ->setMethods(['getName'])
                        ->getMock();

        $courier->method('getName')->willReturn('Example');

        $order = $this->getMockBuilder(Order::class)
                        ->setMethods(['getId'])
                        ->disableOriginalConstructor()
                        ->getMock();

        $order->method('getId')->willReturn('AA01');

        $consignment = new Consignment($order, $courier);
        $result = $consignment->getOrder();
        $this->assertEquals($order, $result);
    }
}
