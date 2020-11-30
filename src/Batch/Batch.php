<?php

namespace Dispatch\Batch;

/**
 * A batch of shipping consignments
 *
 */
class Batch
{
    /**
     * The consignments in the batch
     *
     * @var \Dispatch\Consignment\Consignment[]
     *
     */
    private $consignments = [];

    /**
     * Represents the batch open/closed state
     *
     * @var bool
     *
     */
    private $isClosed = false;


    /**
     * Add a consignment to the batch
     *
     * Consignments are rejected if the consignment is a duplicate or if the batch has been closed
     *
     * @param \Dispatch\Consignment\Consignment $consignment
     * @return Batch
     *
     * @throws BatchClosedException
     * @throws DuplicateConsignmentException
     * @throws InvalidArgumentException
     *
     */
    public function addConsignment(\Dispatch\Consignment\Consignment $consignment): Batch
    {
        if ($this->isClosed()) {
            throw new BatchClosedException("Trying to add a consignment to a closed batch");
        }

        if (empty($consignment->getCourierName())) {
            throw new \InvalidArgumentException("Trying to add a consignment with an empty courier name");
        }

        if (empty($consignment->getConsignmentId())) {
            throw new \InvalidArgumentException("Trying to add a consignment with an empty ID");
        }

        if ($this->isDuplicate($consignment)) {
            throw new DuplicateConsignmentException("Trying to add a consignment with a duplicate ID: "
                . $consignment->getConsignmentId()
                . " from courier: "
                . $consignment->getCourierName());
        }

        $this->consignments[] = $consignment;

        return $this;
    }


    /**
     * Close the consignment
     *
     * @return Batch
     *
     */
    public function close(): Batch
    {
        $this->isClosed = true;

        return $this;
    }


    /**
     * Get all consignments
     *
     * @return \Dispatch\Consignment\Consignment[]
     *
     */
    public function getAllConsignments(): array
    {
        return $this->consignments;
    }


    /**
     * Get all consignments assigned to the given courier object
     *
     * @param \Dispatch\Couriers\CourierInterface $courier
     * @return \Dispatch\Consignment\Consignment[]
     *
     * @throws InvalidArgumentException
     *
     */
    public function getConsignmentsByCourier(\Dispatch\Couriers\CourierInterface $courier): array
    {
        return $this->getConsignmentsByCourierName($courier->getName());
    }


    /**
     * Get all consignments assigned to the named courier
     *
     * @param string $name
     * @return \Dispatch\Consignment\Consignment[]
     *
     * @throws InvalidArgumentException
     *
     */
    public function getConsignmentsByCourierName(string $name): array
    {
        if (empty($name)) {
            throw new \InvalidArgumentException("Trying to get consignments with an empty courier name");
        }

        $filteredConsignments = [];

        foreach ($this->consignments as $consignment) {
            if ($consignment->getCourierName() === $name) {
                $filteredConsignments[] = $consignment;
            }
        }

        return $filteredConsignments;
    }


    /**
     * Is the batch closed?
     *
     * @return bool
     *
     */
    public function isClosed(): bool
    {
        return $this->isClosed;
    }


    /**
     * Check if a consignment is a duplicate
     *
     * Checks if the batch has a consignment with the same courier name and consignment ID.
     * This is done before adding a consignment to the batch.
     *
     * @param \Dispatch\Consignment\Consignment $candidateConsignment
     * @return bool
     *
     */
    private function isDuplicate(\Dispatch\Consignment\Consignment $candidateConsignment): bool
    {
        $candidateId = $candidateConsignment->getConsignmentId();
        $candidateName = $candidateConsignment->getCourierName();

        foreach ($this->consignments as $consignment) {
            if (
                $consignment->getConsignmentId() === $candidateId
                && $consignment->getCourierName() === $candidateName
            ) {
                return true;
            }
        }

        return false;
    }
}
