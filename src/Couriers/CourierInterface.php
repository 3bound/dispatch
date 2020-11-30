<?php

namespace Dispatch\Couriers;

/**
 * A courier for shipping consignments
 *
 */
interface CourierInterface
{
    /**
     * Generate a unique consignment identifier
     *
     * @return string
     *
     */
    public function generateConsignmentId(): string;

    /**
     * Get the courier name
     *
     * @return string
     *
     */
    public function getName(): string;
}
