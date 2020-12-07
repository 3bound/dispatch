<?php

namespace Dispatch\Domain\Couriers\ExampleCourier;

/**
 * An example implementation of a courier
 *
 * @implements Dispatch\Domain\Couriers\CourierInterface
 *
 */
class ExampleCourier implements \Dispatch\Domain\Couriers\CourierInterface
{
    /**
     * The courier name
     *
     */
    private const NAME = 'Example';

    /**
     * Internal index for generating consignment IDs
     *
     * @var integer
     *
     */
    private $index = 0;


    /**
     * Generate a unique consignment identifier
     *
     * @return string
     *
     */
    public function generateConsignmentId(): string
    {
        $this->index += 1;

        return (string)$this->index;
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
