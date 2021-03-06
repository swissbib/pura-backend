<?php

namespace PuraUserModel\Repository;

use PuraUserModel\Entity\PuraUserEntity;
use PuraUserModel\Storage\PuraUserStorageInterface;

/**
 * Class PuraUserRepository
 *
 * @package PuraUserModel\Repository
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
        $this->puraUserStorage = $puraUserStorage;
    }

    /**
     * Check whether a barcode exits in the Database
     *
     * @return bool
     */
    public function getBarcodeExists($barcode, $libraryCode)
    {
        return $this->puraUserStorage->getBarcodeExists($barcode, $libraryCode);
    }

    /**
     * Get a filtered list of all PuraUsers from a Specific Library
     *
     * @param string $filter      the filter string
     * @param string $libraryCode the library code (for example Z01)
     *
     * @return array
     */
    public function getFilteredListOfAllUsersFromALibrary($filter, $libraryCode)
    {
        $puraUsers = $this->puraUserStorage->getFilteredListOfAllUsersFromALibrary($filter, $libraryCode);

        return $puraUsers;
    }

    /**
     * Get all active PuraUsers from a Specific Library
     *
     * @param string $libraryCode the library code (for example Z01)
     *
     * @return array
     */
    public function getAllActiveUsersFromALibrary($libraryCode)
    {
        $puraUsers = $this->puraUserStorage->getAllActiveUsersFromALibrary($libraryCode);

        return $puraUsers;
    }

    /**
     * Get all active expired users
     *
     * @return array
     */
    public function getAllActiveExpiredUsers()
    {
        $puraUsers = $this->puraUserStorage->getAllActiveExpiredUsers();

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

    /**
     * @param $alephNr aleph number
     * @param $barcode barcode of purauser
     * @return the user_id
     */
    public function savePuraUserAlephNr($alephNr, $barcode)
    {
        return $this->puraUserStorage->savePuraUserAlephNr($alephNr, $barcode);
    }

    /**
     * @param $alephNr aleph number
     * @return boolean
     */
    public function savePuraUser($puraUser)
    {
        return $this->puraUserStorage->savePuraUser($puraUser);
    }

    /**
     * Block a user
     *
     * @param string $barcode barcode
     *
     * @return mixed
     */
    public function blockUser($barcode)
    {
        return $this->puraUserStorage->blockUser($barcode);
    }

    /**
     * Unblock a user
     *
     * @param string $barcode barcode
     *
     * @return mixed
     */
    public function unBlockUser($barcode)
    {
        return $this->puraUserStorage->unBlockUser($barcode);
    }

    /**
     * Reset the date of the last reminder email
     *
     * @param string $barcode barcode
     *
     * @return mixed
     */
    public function resetReminderEmail($barcode)
    {
        return $this->puraUserStorage->resetReminderEmail($barcode);
    }

    /**
     * Get the library codes of all libraries where the user is registered and active
     *
     * @param string $eduid EduId number like 169330697816@eduid.ch
     *
     * @return array $libraryCodes An array of Library Codes
     */
    public function getAllActiveLibrariesForUser($eduid)
    {
        return $this->puraUserStorage->getAllActiveLibrariesForUser($eduid);
    }
}