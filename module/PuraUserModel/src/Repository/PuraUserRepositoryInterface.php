<?php

namespace PuraUserModel\Repository;
use PuraUserModel\Entity\PuraUserEntity;

/**
 * Interface PuraUserRepositoryInterface
 *
 * @package PuraUserModel\Repository
 */
interface PuraUserRepositoryInterface
{
    /**
     * Check whether a barcode exits in the Database
     *
     * @return bool
     */
    public function getBarcodeExists($barcode, $libraryCode);

    /**
     * Get filtered list of all PuraUsers
     *
     * @param String $filer
     *
     * @return array
     */
    public function getFilteredListOfAllUsers($filter);

    /**
     * Get a filtered list of all PuraUsers from a Specific Library
     *
     * @param string $filter      the filter string
     * @param string $libraryCode the library code (for example Z01)
     *
     * @return array
     */
    public function getFilteredListOfAllUsersFromALibrary($filter, $libraryCode);

    /**
     * Get single PuraUser by barcode
     *
     * @param string $barcode
     *
     * @return PuraUserEntity
     */
    public function getSinglePuraUser($barcode);

    /**
     * @param $alephNr aleph number
     * @param $barcode barcode of purauser
     * @return the number of records affected
     */
    public function savePuraUserAlephNr($alephNr, $barcode);

    /**
     * @param $puraUser PuraUserEntity
     * @return boolean
     */
    public function savePuraUser($puraUser);

    /**
     * Block a user
     *
     * @param string $barcode barcode
     *
     * @return mixed
     */
    public function blockUser($barcode);
}
