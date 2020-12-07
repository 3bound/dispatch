<?php

namespace Tests\Domain\Order;

use PHPUnit\Framework\TestCase;
use Dispatch\Domain\Order\Order;

final class OrderTest extends TestCase
{
    /**
     * Can the class be instantiated?
     *
     */
    public function testCanBeInstantiated(): void
    {
        $this->assertInstanceOf(
            Order::class,
            new Order('A1', ['test'])
        );
    }


    /**
     * Instantiation fails when the ID is empty
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testCannotInstantiateWithEmptyId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Order('', ['test']);
    }


    /**
     * Instantiation fails when the details are empty
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testCannotInstantiateWithEmptyDetails(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Order('A1', []);
    }


    /**
     * Get the ID
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testGetId(): void
    {
        $order = new Order('A1', ['test']);
        $expected = 'A1';
        $result = $order->getId();
        $this->assertEquals($expected, $result);
    }


    /**
     * Set the ID
     *
     * @depends testGetId
     *
     */
    public function testSetId(): void
    {
        $order = new Order('A1', ['test']);
        $order->setId('A2');
        $expected = 'A2';
        $result = $order->getId();
        $this->assertEquals($expected, $result);
    }


    /**
     * Cannot set empty ID
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testCannotSetEmptyId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $order = new Order('A1', ['test']);
        $order->setId('');
    }


    /**
     * Get the details
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testGetDetails(): void
    {
        $order = new Order('A1', ['test']);
        $expected = ['test'];
        $result = $order->getDetails();
        $this->assertEquals($expected, $result);
    }


    /**
     * Set the details
     *
     * @depends testGetDetails
     *
     */
    public function testSetDetails(): void
    {
        $order = new Order('A1', ['test']);
        $order->setDetails(['test2']);
        $expected = ['test2'];
        $result = $order->getDetails();
        $this->assertEquals($expected, $result);
    }


    /**
     * Cannot set empty details
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testCannotSetEmptyDetails(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $order = new Order('A1', ['test']);
        $order->setDetails([]);
    }
}
