<?php

namespace Dispatch\Domain\Consignment;

use Dispatch\Domain\Couriers\CourierInterface;
use Dispatch\Domain\Order\Order;

/**
 * A shipping consignment
 *
 */
class Consignment
{

    /**
     * The unique ID
     *
     * Generated by the courier's algorithm
     *
     * @var string
     *
     */
    private $consignmentId;

    /**
     * The courier assigned to this consignment
     *
     * @var CourierInterface
     *
     */
    private $courier;

    /**
     * The name of the courier
     *
     * @var string
     *
     */
    private $courierName;

    /**
     * The order
     *
     * @var Order
     *
     */
    private $order;


    /**
     * @param Order $order
     * @param CourierInterface $courier
     *
     */
    public function __construct(Order $order, CourierInterface $courier)
    {
        $this->courier = $courier;
        $this->order = $order;
        $this->setConsignmentId($this->courier->generateConsignmentId($this->order));
        $this->setCourierName($this->courier->getName());
    }


    /**
     * Set the consignment ID
     *
     * @param string $id
     * @return void
     *
     * @throws InvalidArgumentException
     *
     */
    private function setConsignmentId(string $id): void
    {
        if (empty($id)) {
            throw new \InvalidArgumentException("Empty consignment ID generated by " . get_class($this->courier));
        }

        $this->consignmentId = $id;
    }


    /**
     * Set the courier name
     *
     * @param string $name
     * @return void
     *
     * @throws InvalidArgumentException
     *
     */
    private function setCourierName(string $name): void
    {
        if (empty($name)) {
            throw new \InvalidArgumentException("Empty courier name supplied by " . get_class($this->courier));
        }

        $this->courierName = $name;
    }


    /**
     * Get the ID
     *
     * @return string
     *
     */
    public function getId(): string
    {
        return $this->consignmentId;
    }


    /**
     * Get the courier name
     *
     * @return string
     *
     */
    public function getCourierName(): string
    {
        return $this->courierName;
    }


    /**
     * Get the courier
     *
     * @return CourierInterface
     *
     */
    public function getCourier(): CourierInterface
    {
        return $this->courier;
    }


    /**
     * Get the order
     *
     * @return Order
     *
     */
    public function getOrder(): Order
    {
        return $this->order;
    }
}
