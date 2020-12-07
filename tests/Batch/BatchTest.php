<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Dispatch\Domain\Batch\Batch;
use Dispatch\Domain\Batch\BatchClosedException;
use Dispatch\Domain\Batch\DuplicateConsignmentException;
use Dispatch\Domain\Consignment\Consignment;
use Dispatch\Domain\Couriers\ExampleCourier\ExampleCourier;

final class BatchTest extends TestCase
{
    /**
     * Can the class be instantiated?
     *
     */
    public function testCanBeInstantiated(): void
    {
        $this->assertInstanceOf(
            Batch::class,
            new Batch()
        );
    }


    /**
     * Is a newly instantiated batch open to receive consignments?
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testBatchIsOpenUponInstantiation(): void
    {
        $batch = new Batch();
        $this->assertFalse($batch->isClosed());
    }


    /**
     * Can an open batch be closed?
     *
     * @depends testBatchIsOpenUponInstantiation
     *
     */
    public function testBatchCanBeClosed(): void
    {
        $batch = new Batch();
        $batch->close();
        $this->assertTrue($batch->isClosed());
    }


    /**
     * Can a valid consignment be added to an empty batch?
     *
     * @depends testBatchIsOpenUponInstantiation
     *
     */
    public function testAddConsignmentToEmptyBatch(): void
    {
        $consignment = $this->getMockBuilder(Consignment::class)
                            ->disableOriginalConstructor()
                            ->setMethods(['getConsignmentId', 'getCourierName'])
                            ->getMock();

        $consignment->method('getConsignmentId')->willReturn('A12345');
        $consignment->method('getCourierName')->willReturn('Example');

        $batch = new Batch();
        $batch->addConsignment($consignment);
        $expected = [$consignment];
        $result = $batch->getAllConsignments();
        $this->assertEquals($expected, $result);
    }


    /**
     * Can a valid consignment be added to an non-empty batch?
     *
     * @depends testAddConsignmentToEmptyBatch
     *
     */
    public function testAddConsignmentToNonEmptyBatch(): void
    {
        $consignment1 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment2 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment1->method('getConsignmentId')->willReturn('A12345');
        $consignment1->method('getCourierName')->willReturn('Example 1');
        $consignment2->method('getConsignmentId')->willReturn('B6789');
        $consignment2->method('getCourierName')->willReturn('Example 2');

        $batch = new Batch();
        $batch->addConsignment($consignment1)->addConsignment($consignment2);
        $expected = [$consignment1, $consignment2];
        $result = $batch->getAllConsignments();
        $this->assertEquals($expected, $result);
    }


    /**
     * Does a closed batch reject new consignments?
     *
     * @depends testBatchCanBeClosed
     * @depends testAddConsignmentToEmptyBatch
     *
     */
    public function testClosedBatchRejectsNewConsignments(): void
    {
        $consignment = $this->getMockBuilder(Consignment::class)
                            ->disableOriginalConstructor()
                            ->setMethods(['getConsignmentId', 'getCourierName'])
                            ->getMock();

        $consignment->method('getConsignmentId')->willReturn('A12345');
        $consignment->method('getCourierName')->willReturn('Example');

        $batch = new Batch();
        $batch->close();
        $this->expectException(BatchClosedException::class);
        $batch->addConsignment($consignment);
    }


    /**
     * Does the batch reject consignments with empty courier names?
     *
     * @depends testAddConsignmentToEmptyBatch
     *
     */
    public function testRejectConsignmentWithEmptyCourierName(): void
    {
        $consignment = $this->getMockBuilder(Consignment::class)
                            ->disableOriginalConstructor()
                            ->setMethods(['getConsignmentId', 'getCourierName'])
                            ->getMock();

        $consignment->method('getConsignmentId')->willReturn('A12345');
        $consignment->method('getCourierName')->willReturn('');

        $batch = new Batch();
        $this->expectException(\InvalidArgumentException::class);
        $batch->addConsignment($consignment);
    }


    /**
     * Does the batch reject consignments with empty IDs?
     *
     * @depends testAddConsignmentToEmptyBatch
     *
     */
    public function testRejectConsignmentWithEmptyId(): void
    {
        $consignment = $this->getMockBuilder(Consignment::class)
                            ->disableOriginalConstructor()
                            ->setMethods(['getConsignmentId', 'getCourierName'])
                            ->getMock();

        $consignment->method('getConsignmentId')->willReturn('');
        $consignment->method('getCourierName')->willReturn('Example');

        $batch = new Batch();
        $this->expectException(\InvalidArgumentException::class);
        $batch->addConsignment($consignment);
    }


    /**
     * Does the batch reject duplicate consignments?
     *
     * @depends testAddConsignmentToNonEmptyBatch
     *
     */
    public function testRejectDuplicateConsignments(): void
    {
        $consignment1 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment2 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment1->method('getConsignmentId')->willReturn('A12345');
        $consignment1->method('getCourierName')->willReturn('Example');
        $consignment2->method('getConsignmentId')->willReturn('A12345');
        $consignment2->method('getCourierName')->willReturn('Example');

        $batch = new Batch();
        $this->expectException(DuplicateConsignmentException::class);
        $batch->addConsignment($consignment1);
        $batch->addConsignment($consignment2);
    }


    /**
     * Does the batch accept consignments with duplicate IDs and different couriers?
     *
     * @depends testAddConsignmentToNonEmptyBatch
     *
     */
    public function testAcceptConsignmentWithDuplicateIdAndDifferentCourier(): void
    {
        $consignment1 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment2 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment1->method('getConsignmentId')->willReturn('A12345');
        $consignment1->method('getCourierName')->willReturn('Example 1');
        $consignment2->method('getConsignmentId')->willReturn('A12345');
        $consignment2->method('getCourierName')->willReturn('Example 2');

        $batch = new Batch();
        $batch->addConsignment($consignment1);
        $batch->addConsignment($consignment2);
        $expected = [$consignment1, $consignment2];
        $result = $batch->getAllConsignments();
        $this->assertSame($expected, $result);
    }


    /**
     * Does the batch accept consignments with duplicate couriers and different IDs?
     *
     * @depends testAddConsignmentToNonEmptyBatch
     *
     */
    public function testAcceptConsignmentWithDuplicateCourierAndDifferentId(): void
    {
        $consignment1 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment2 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment1->method('getConsignmentId')->willReturn('A12345');
        $consignment1->method('getCourierName')->willReturn('Example');
        $consignment2->method('getConsignmentId')->willReturn('B12345');
        $consignment2->method('getCourierName')->willReturn('Example');

        $batch = new Batch();
        $batch->addConsignment($consignment1);
        $batch->addConsignment($consignment2);
        $expected = [$consignment1, $consignment2];
        $result = $batch->getAllConsignments();
        $this->assertSame($expected, $result);
    }


    /**
     * ::getAllConsignments returns empty array when the batch is empty
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testGetAllConsignmentsWhenBatchEmpty(): void
    {
        $batch = new Batch();
        $expected = [];
        $result = $batch->getAllConsignments();
        $this->assertSame($expected, $result);
    }


    /**
     * ::getAllConsignments returns consignments
     *
     * @depends testAddConsignmentToNonEmptyBatch
     *
     */
    public function testGetAllConsignments(): void
    {
        $consignment1 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment2 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment1->method('getConsignmentId')->willReturn('A12345');
        $consignment1->method('getCourierName')->willReturn('Example 1');
        $consignment2->method('getConsignmentId')->willReturn('B12345');
        $consignment2->method('getCourierName')->willReturn('Example 2');

        $batch = new Batch();
        $batch->addConsignment($consignment1);
        $batch->addConsignment($consignment2);
        $expected = [$consignment1, $consignment2];
        $result = $batch->getAllConsignments();
        $this->assertSame($expected, $result);
    }


    /**
     * ::getConsignmentsByCourierName when the batch is empty returns empty array
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testGetConsignmentsByCourierNameWhenBatchEmpty(): void
    {
        $batch = new Batch();
        $expected = [];
        $result = $batch->getConsignmentsByCourierName('Example');
        $this->assertSame($expected, $result);
    }


    /**
     * ::getConsignmentsByCourierName throws exception when passed an empty string
     *
     * @depends testAddConsignmentToNonEmptyBatch
     *
     */
    public function testGetConsignmentsByCourierNameEmptyString(): void
    {
        $consignment1 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment2 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment1->method('getConsignmentId')->willReturn('A12345');
        $consignment1->method('getCourierName')->willReturn('Example 1');
        $consignment2->method('getConsignmentId')->willReturn('B12345');
        $consignment2->method('getCourierName')->willReturn('Example 2');

        $batch = new Batch();
        $batch->addConsignment($consignment1);
        $batch->addConsignment($consignment2);
        $this->expectException(\InvalidArgumentException::class);
        $result = $batch->getConsignmentsByCourierName('');
    }


    /**
     * ::getConsignmentsByCourierName returns empty array when no match found
     *
     * @depends testAddConsignmentToNonEmptyBatch
     *
     */
    public function testGetConsignmentsByCourierNameNoMatchFound(): void
    {
        $consignment1 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment2 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment1->method('getConsignmentId')->willReturn('A12345');
        $consignment1->method('getCourierName')->willReturn('Example 1');
        $consignment2->method('getConsignmentId')->willReturn('B12345');
        $consignment2->method('getCourierName')->willReturn('Example 2');

        $batch = new Batch();
        $batch->addConsignment($consignment1);
        $batch->addConsignment($consignment2);
        $expected = [];
        $result = $batch->getConsignmentsByCourierName('Example 3');
        $this->assertSame($expected, $result);
    }


    /**
     * ::getConsignmentsByCourierName returns matches
     *
     * @depends testAddConsignmentToNonEmptyBatch
     *
     */
    public function testGetConsignmentsByCourierNameReturnsMatches(): void
    {
        $consignment1 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment2 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment3 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment4 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment1->method('getConsignmentId')->willReturn('A12345');
        $consignment1->method('getCourierName')->willReturn('Example 1');
        $consignment2->method('getConsignmentId')->willReturn('B12345');
        $consignment2->method('getCourierName')->willReturn('Example 2');
        $consignment3->method('getConsignmentId')->willReturn('C12345');
        $consignment3->method('getCourierName')->willReturn('Example 1');
        $consignment4->method('getConsignmentId')->willReturn('D12345');
        $consignment4->method('getCourierName')->willReturn('Example 3');

        $batch = new Batch();
        $batch->addConsignment($consignment1);
        $batch->addConsignment($consignment2);
        $batch->addConsignment($consignment3);
        $batch->addConsignment($consignment4);
        $expected = [$consignment1, $consignment3];
        $result = $batch->getConsignmentsByCourierName('Example 1');
        $this->assertSame($expected, $result);
    }


    /**
     * ::getConsignmentsByCourier throws exception when courier name is empty
     *
     * @depends testAddConsignmentToEmptyBatch
     *
     */
    public function testGetConsignmentsByCourierWhenCourierNameEmpty(): void
    {
        $consignment1 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment1->method('getConsignmentId')->willReturn('A12345');
        $consignment1->method('getCourierName')->willReturn('Example 1');

        $courier = $this->getMockBuilder(\Dispatch\Domain\Couriers\ExampleCourier\ExampleCourier::class)
                        ->disableOriginalConstructor()
                        ->setMethods(['getName'])
                        ->getMock();

        $courier->method('getName')->willReturn('');

        $batch = new Batch();
        $batch->addConsignment($consignment1);
        $this->expectException(\InvalidArgumentException::class);
        $result = $batch->getConsignmentsByCourier($courier);
    }


    /**
     * ::getConsignmentsByCourier returns empty array when the batch is empty
     *
     * @depends testCanBeInstantiated
     *
     */
    public function testGetConsignmentsByCourierFromEmptyBatch(): void
    {
        $courier = $this->getMockBuilder(\Dispatch\Domain\Couriers\ExampleCourier\ExampleCourier::class)
                        ->disableOriginalConstructor()
                        ->setMethods(['getName'])
                        ->getMock();

        $courier->method('getName')->willReturn('Example');

        $batch = new Batch();
        $expected = [];
        $result = $batch->getConsignmentsByCourier($courier);
        $this->assertSame($expected, $result);
    }


    /**
     * ::getConsignmentsByCourier returns empty array when no matches found
     *
     * @depends testAddConsignmentToEmptyBatch
     *
     */
    public function testGetConsignmentsByCourierNoMatchesFound(): void
    {
        $consignment1 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment1->method('getConsignmentId')->willReturn('A12345');
        $consignment1->method('getCourierName')->willReturn('Example 1');

        $courier = $this->getMockBuilder(\Dispatch\Domain\Couriers\ExampleCourier\ExampleCourier::class)
                        ->disableOriginalConstructor()
                        ->setMethods(['getName'])
                        ->getMock();

        $courier->method('getName')->willReturn('Example 2');

        $batch = new Batch();
        $batch->addConsignment($consignment1);
        $expected = [];
        $result = $batch->getConsignmentsByCourier($courier);
        $this->assertSame($expected, $result);
    }


    /**
     * ::getConsignmentsByCourier finds matches
     *
     * @depends testAddConsignmentToEmptyBatch
     *
     */
    public function testGetConsignmentsByCourierFindsMatches(): void
    {
        $consignment1 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment2 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment3 = $this->getMockBuilder(Consignment::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getConsignmentId', 'getCourierName'])
                             ->getMock();

        $consignment1->method('getConsignmentId')->willReturn('A12345');
        $consignment1->method('getCourierName')->willReturn('Example 1');
        $consignment2->method('getConsignmentId')->willReturn('B12345');
        $consignment2->method('getCourierName')->willReturn('Example 2');
        $consignment3->method('getConsignmentId')->willReturn('C12345');
        $consignment3->method('getCourierName')->willReturn('Example 1');

        $courier = $this->getMockBuilder(\Dispatch\Domain\Couriers\ExampleCourier\ExampleCourier::class)
                        ->disableOriginalConstructor()
                        ->setMethods(['getName'])
                        ->getMock();

        $courier->method('getName')->willReturn('Example 1');

        $batch = new Batch();
        $batch->addConsignment($consignment1);
        $batch->addConsignment($consignment2);
        $batch->addConsignment($consignment3);
        $expected = [$consignment1, $consignment3];
        $result = $batch->getConsignmentsByCourier($courier);
        $this->assertSame($expected, $result);
    }
}
