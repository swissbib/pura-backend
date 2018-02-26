<?php

namespace PuraUser\Model\Repository;

use PuraUser\Model\Storage\PuraUserStorageInterface;

/**
 * Class PuraUserRepository
 *
 * @package PuraUser\Model\Repository
 */
class PuraUserRepository implements PuraUserRepositoryInterface
{
    /**
     * @var PuraUserStorageInterface
     */
    private $puraUserStorage;

    /**
     * PuraUserRepository constructor.
     *
     * @param PuraUserStorageInterface      $puraUserStorage
     */
    public function __construct(
        PuraUserStorageInterface $puraUserStorage
    ) {
        $this->puraUserStorage      = $puraUserStorage;
    }

    /**
     * Get list of all PuraUsers
     *
     * @return array
     */
    public function getListOfAllUsers()
    {
        $puraUsers = $this->puraUserStorage->getListOfAllUsers();

        return $puraUsers;
    }

    /**
     * Get filtered list of all PuraUsers
     *
     * @param String $filter

     * @return array
     */
    public function getFilteredListOfAllUsers($filter)
    {
        $puraUsers = $this->puraUserStorage->getFilteredListOfAllUsers($filter);

        return $puraUsers;
    }

    /**
     * Get single PuraUser by barcode
     *
     * @param integer $barcode
     *
     * @return array
     */
    public function getSinglePuraUserByBarcode($barcode)
    {
        $puraUser = $this->puraUserStorage->getSinglePuraUserByBarcode($barcode);

        if (!$puraUser) {
            return false;
        }

        return $puraUser;
    }

    /**
     * Get single PuraUser by user_id
     *
     * @param integer $userId
     *
     * @return array
     */
    public function getSinglePuraUserByUserId($userId)
    {
        $puraUser = $this->puraUserStorage->getSinglePuraUserByUserId($userId);

        if (!$puraUser) {
            return false;
        }

        return $puraUser;
    }

    /**
     * @param $alephNr aleph number
     * @param $barcode barcode of purauser
     * @return the user_id
     */
    public function savePuraUserAlephNrIdentifiedByBarcode($alephNr, $barcode)
    {
        return $this->puraUserStorage->savePuraUserAlephNrIdentifiedByBarcode($alephNr, $barcode);
    }

    /**
     * @param $alephNr aleph number
     * @return boolean
     */
    public function savePuraUser($puraUser)
    {
        return $this->puraUserStorage->savePuraUser($puraUser);
    }
}