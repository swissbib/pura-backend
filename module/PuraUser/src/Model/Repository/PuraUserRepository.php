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
    public function getSinglePuraUser($barcode)
    {
        $puraUser = $this->puraUserStorage->getSinglePuraUser($barcode);

        if (!$puraUser) {
            return false;
        }

        return $puraUser;
    }

}