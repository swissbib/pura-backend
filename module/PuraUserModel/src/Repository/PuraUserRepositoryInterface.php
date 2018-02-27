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
     * Get list of all PuraUsers
     *
     * @return array
     */
    public function getListOfAllUsers();

    /**
     * Get fitlered list of all PuraUsers
     *
     * @param String $filer
     *
     * @return array
     */
    public function getFilteredListOfAllUsers($filter);

    /**
     * Get single PuraUser by user_id
     *
     * @param integer $userId
     *
     * @return PuraUserEntity
     */
    public function getSinglePuraUserByUserId($userId);

    /**
     * Get single PuraUser by barcode
     *
     * @param integer $barcode
     *
     * @return PuraUserEntity
     */
    public function getSinglePuraUserByBarcode($barcode);

    /**
     * @param $alephNr aleph number
     * @param $barcode barcode of purauser
     * @return the number of records affected
     */
    public function savePuraUserAlephNrIdentifiedByBarcode($alephNr, $barcode);

    /**
     * @param $puraUser PuraUserEntity
     * @return boolean
     */
    public function savePuraUser($puraUser);
}
