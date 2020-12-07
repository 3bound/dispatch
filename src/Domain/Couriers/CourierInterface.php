<?php

namespace Dispatch\Domain\Couriers;

use Dispatch\Domain\Order\Order;

/**
 * A courier for shipping consignments
 *
 */
interface CourierInterface
{
    /**
     * Generate a unique consignment identifier
     *
     * @param Order $order
     * @return string
     *
     * @throws \InvalidArgumentException
     *
     */
    public function generateConsignmentId(Order $order): string;

    /**
     * Get the courier name
     *
     * @return string
     *
     */
    public function getName(): string;
}
