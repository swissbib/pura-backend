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
     * Get fitlered list of all PuraUsers
     *
     * @param String $filer
     *
     * @return array
     */
    public function getFilteredListOfAllUsers($filter);

    /**
     * Get single PuraUser by barcode
     *
     * @param integer $barcode
     *
     * @return array
     */
    public function getSinglePuraUser($barcode);

    /**
     * @param $alephNr aleph number
     * @param $barcode barcode of purauser
     * @return the user_id
     */
    public function savePuraUserAlephNrIdentifiedByBarcode($alephNr, $barcode);
}
