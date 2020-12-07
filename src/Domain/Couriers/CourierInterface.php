<?php

namespace Dispatch\Domain\Couriers;

use Dispatch\Domain\Order\OrderInterface;

/**
 * A courier interface for shipping consignments
 *
 */
interface CourierInterface
{
    /**
     * Generate a unique consignment identifier
     *
     * @param OrderInterface $order
     * @return string
     *
     * @throws \InvalidArgumentException
     *
     */
    public function generateConsignmentId(OrderInterface $order): string;

    /**
     * Get the courier name
     *
     * @return string
     *
     */
    public function getName(): string;
}
