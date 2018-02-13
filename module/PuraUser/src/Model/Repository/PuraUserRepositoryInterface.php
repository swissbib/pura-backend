<?php

namespace PuraUser\Model\Repository;

/**
 * Interface PuraUserRepositoryInterface
 *
 * @package PuraUser\Model\Repository
 */
interface PuraUserRepositoryInterface
{
    /**
     * Get list of all PuraUsers
     *
     * @return array
     */
    public function getListOfAllUsers();

    /**
     * Get single PuraUser by barcode
     *
     * @param integer $barcode
     *
     * @return array
     */
    public function getSinglePuraUser($barcode);
}
