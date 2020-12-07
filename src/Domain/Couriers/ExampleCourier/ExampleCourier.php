<?php

namespace Dispatch\Domain\Couriers\ExampleCourier;

use Dispatch\Domain\Order\Order;
use Dispatch\Domain\Couriers\CourierInterface;

/**
 * An example implementation of a courier
 *
 * @implements CourierInterface
 *
 */
class ExampleCourier implements CourierInterface
{
    /**
     * The courier name
     *
     */
    private const NAME = 'Example';


    /**
     * Generate a unique consignment identifier
     *
     * @param Order $order
     * @return string
     *
     */
    public function generateConsignmentId(Order $order): string
    {
        $nanoseconds = (string)hrtime(true);
        $orderId = $order->getId();
        $consignmentId = base64_encode($nanoseconds . $orderId . random_bytes(8));

        return $consignmentId;
    }


    /**
     * Get the courier name
     *
     * @return string
     *
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
