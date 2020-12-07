<?php

namespace Dispatch\Domain\Order;

/**
 * Order interface
 *
 */
interface OrderInterface
{
    /**
     * Set the otder ID
     *
     * @param string $id
     * @return void
     *
     * @throws \InvalidArgumentException
     *
     */
    public function setId(string $id): void;

    /**
     * Get the order ID
     *
     * @return string
     *
     */
    public function getId(): string;


    /**
     * Set the order details
     *
     * @param array $details
     * @return void
     *
     * @throws \InvalidArgumentException
     *
     */
    public function setDetails(array $details): void;


    /**
     * Get the order details
     *
     * @return array
     *
     */
    public function getDetails(): array;
}
