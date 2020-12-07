<?php

namespace Dispatch\Domain\Order;

/**
 * A stub implementation of an Order
 *
 * @implements OrderInterface
 *
 */
class Order implements OrderInterface
{
    /**
     * The order ID
     *
     * @var string
     *
     */
    private $id;

    /**
     * The order details
     *
     * @var array
     *
     */
    private $details;


    /**
     * Constructor
     *
     * @param string $id
     * @param array $details
     *
     * @throws \InvalidArgumentException
     *
     */
    public function __construct(string $id, array $details)
    {
        $this->setId($id);
        $this->setDetails($details);
    }


    /**
     * Set the order ID
     *
     * @param string $id
     * @return void
     *
     * @throws \InvalidArgumentException
     *
     */
    public function setId(string $id): void
    {
        if (empty($id)) {
            throw new \InvalidArgumentException("Order ID cannot be empty");
        }

        $this->id = $id;
    }


    /**
     * Get the order ID
     *
     * @return string
     *
     */
    public function getId(): string
    {
        return $this->id;
    }


    /**
     * Set the order details
     *
     * @param array $details
     * @return void
     *
     * @throws \InvalidArgumentException
     *
     */
    public function setDetails(array $details): void
    {
        if (empty($details)) {
            throw new \InvalidArgumentException("Order details cannot be empty");
        }

        $this->details = $details;
    }


    /**
     * Get the order details
     *
     * @return array
     *
     */
    public function getDetails(): array
    {
        return $this->details;
    }
}
