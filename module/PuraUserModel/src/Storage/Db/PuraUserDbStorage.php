<?php

namespace PuraUserModel\Storage\Db;

use PuraUserModel\Entity\PuraUserEntity;
use PuraUserModel\Storage\PuraUserStorageInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\TableGatewayInterface;

/**
 * Class PuraUserDbStorage
 *
 * @package PuraUserModel\Storage\Db
 */
class PuraUserDbStorage implements PuraUserStorageInterface
{
    /**
     * @var TableGatewayInterface|AbstractTableGateway
     */
    private $tableGateway;

    /**
     * PuraUserDbStorage constructor.
     *
     * @param TableGatewayInterface $tableGateway
     */
    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Check whether a barcode exits in the Database
     *
     * @return bool
     */
    public function getBarcodeExists($barcode, $libraryCode)
    {
        $select = $this->tableGateway->getSql()->select();

        if($libraryCode == 'admin') {
            $select->where->equalTo('barcode', $barcode)
                ->and->like('library_code', '%');
        } else {
            $select->where->equalTo('barcode', $barcode)
                ->and->like('library_code', $libraryCode);
        }

        /** @var ResultSet $resultSet */
        $resultSet = $this->tableGateway->selectWith($select);
        if ($resultSet->count() < 1) {
            return false;
        } else {
            return true;
        }
    }

    private function createPuraUserEntity($puraUserArrayObject)
    {
        $puraUserArray = (array)$puraUserArrayObject;
        $puraUserEntity = new PuraUserEntity();
        $puraUserEntity->setUserId(
            !array_key_exists('user_id', $puraUserArray) ?
                null : $puraUserArray['user_id']
        );
        $puraUserEntity->setEduId(
            !array_key_exists('edu_id', $puraUserArray) ?
                null : $puraUserArray['edu_id']
        );
        $puraUserEntity->setBarcode(
            !array_key_exists('barcode', $puraUserArray) ?
                null : $puraUserArray['barcode']
        );
        $puraUserEntity->setAccessCreated(
            !array_key_exists('access_created', $puraUserArray) ?
                null : $puraUserArray['access_created']
        );
        $puraUserEntity->setDateExpiration(
            !array_key_exists('date_expiration', $puraUserArray) ?
                null : $puraUserArray['date_expiration']
        );
        $puraUserEntity->setRemarks(
            !array_key_exists('remarks', $puraUserArray) ?
                null : $puraUserArray['remarks']
        );
        $puraUserEntity->setLibrarySystemNumber(
            !array_key_exists('library_system_number', $puraUserArray) ?
                null : $puraUserArray['library_system_number']
        );
        $puraUserEntity->setFirstname(
            !array_key_exists('firstname', $puraUserArray) ?
                null : $puraUserArray['firstname']
        );
        $puraUserEntity->setLastname(
            !array_key_exists('lastname', $puraUserArray) ?
                null : $puraUserArray['lastname']
        );
        $puraUserEntity->setEmail(
            !array_key_exists('email', $puraUserArray) ?
                null : $puraUserArray['email']
        );
        $puraUserEntity->setLibraryCode(
            !array_key_exists('library_code', $puraUserArray) ?
                null : $puraUserArray['library_code']
        );
        $puraUserEntity->setHasAccess(
            !array_key_exists('has_access', $puraUserArray) ?
                null : $puraUserArray['has_access']
        );
        $puraUserEntity->setBlocked(
            !array_key_exists('blocked', $puraUserArray) ?
                null : $puraUserArray['blocked']
        );
        $puraUserEntity->setBlockedCreated(
            !array_key_exists('blocked_created', $puraUserArray) ?
                null : $puraUserArray['blocked_created']
        );
        $puraUserEntity->setIsMemberEducationInstitution(
            !array_key_exists('is_member_education_institution', $puraUserArray) ?
                null : $puraUserArray['is_member_education_institution']
        );
        return $puraUserEntity;
    }

    /**
     * Get PuraUser by barcode
     *
     * @param integer $barcode
     *
     * @return array
     */
    public function getSinglePuraUser($barcode)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['user_id','edu_id','barcode', 'access_created', 'date_expiration', 'remarks', 'library_system_number', 'library_code', 'has_access', 'blocked', 'blocked_created', 'is_member_education_institution']);
        $select->where->equalTo('barcode', $barcode);
        $select->join('user', 'user.id = pura_user.user_id', ['firstname', 'lastname', 'email'], 'left');

        /** @var ResultSet $resultSet */
        $resultSet = $this->tableGateway->selectWith($select);
        if ($resultSet->count() < 1) return new PuraUserEntity();
        $puraUserEntity = $this->createPuraUserEntity($resultSet->current());

        return $puraUserEntity;
    }

    /**
     * Get a filtered list of all PuraUsers
     *
     * @param string $filter the filter string
     *
     * @return array
     */
    public function getFilteredListOfAllUsers($filter)
    {
        $filter = '%' . $filter . '%';
        $select = $this->tableGateway->getSql()->select()
            ->columns(['user_id','edu_id','barcode']);
        $select->where->nest()
            ->like('pura_user.barcode', $filter)
            ->or->like('pura_user.library_system_number', $filter)
            ->or->like('user.firstname', $filter)
            ->or->like('user.lastname', $filter)
            ->or->like('user.email', $filter)
            ->unnest();
        $select->join('user', 'user.id = pura_user.user_id', ['firstname','lastname'], 'left');

        $puraUserEntityArray = [];
        $puraUserEntity = new PuraUserEntity();

        foreach ($this->tableGateway->selectWith($select) as $row) {
            $puraUserEntity = $this->createPuraUserEntity($row);
            $puraUserEntityArray[] = $puraUserEntity;
        }
        return $puraUserEntityArray;
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
        $filter = '%' . $filter . '%';
        $select = $this->tableGateway->getSql()->select()
            ->columns(['user_id','edu_id','barcode']);
        $select->where->nest()
            ->like('pura_user.barcode', $filter)
            ->or->like('pura_user.library_system_number', $filter)
            ->or->like('user.firstname', $filter)
            ->or->like('user.lastname', $filter)
            ->or->like('user.email', $filter)
            ->unnest();

        if ($libraryCode == 'admin') {
            $select->where->and->like('pura_user.library_code', '%');
        } else {
            $select->where->and->like('pura_user.library_code', $libraryCode);
        }
        $select->join('user', 'user.id = pura_user.user_id', ['firstname','lastname'], 'left');

        $puraUserEntityArray = [];
        $puraUserEntity = new PuraUserEntity();

        foreach ($this->tableGateway->selectWith($select) as $row) {
            $puraUserEntity = $this->createPuraUserEntity($row);
            $puraUserEntityArray[] = $puraUserEntity;
        }
        return $puraUserEntityArray;
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
        $select = $this->tableGateway->getSql()->select();

        $select->where->like('library_code', $libraryCode);
        $select->where->and->like('has_access', true);

        $puraUserEntityArray = [];
        $puraUserEntity = new PuraUserEntity();

        foreach ($this->tableGateway->selectWith($select) as $row) {
            $puraUserEntity = $this->createPuraUserEntity($row);
            $puraUserEntityArray[] = $puraUserEntity;
        }
        return $puraUserEntityArray;
    }

    public function savePuraUserAlephNr($alephNr, $barcode)
    {
        $update = $this->tableGateway->getSql()->update();
        $update->set(
            [
                'library_system_number' => $alephNr,
            ]
        );
        $update->where->equalTo('barcode', $barcode);
        $dbRetVal = $this->tableGateway->updateWith($update);

        return $dbRetVal;
    }

    public function blockUser($barcode)
    {
        $update = $this->tableGateway->getSql()->update();
        $update->set(
            [
                'access_created' => null,
                'has_access' => 0,
                'blocked' => 1,
                'blocked_created' => date("Y-m-d H:i:s"),
                'date_expiration' => null,
            ]
        );

        $update->where->equalTo('barcode', $barcode);
        $dbRetVal = $this->tableGateway->updateWith($update);

        return $dbRetVal;
    }

    public function unBlockUser($barcode)
    {
        $update = $this->tableGateway->getSql()->update();
        $update->set(
            [
                'blocked' => 0,
                'blocked_created' => null,
            ]
        );

        $update->where->equalTo('barcode', $barcode);
        $dbRetVal = $this->tableGateway->updateWith($update);

        return $dbRetVal;
    }

    /* removes the date of the reminder email sent */
    public function resetReminderEmail($barcode)
    {
        $update = $this->tableGateway->getSql()->update();
        $update->set(
            [
                'last_account_extension_request' => null,
            ]
        );

        $update->where->equalTo('barcode', $barcode);
        $dbRetVal = $this->tableGateway->updateWith($update);

        return $dbRetVal;
    }

    /**
     * @param $puraUser PuraUserEntity
     */
    public function savePuraUser($puraUser)
    {
        // read entity and use barcode as primary. use non-empty fields OR all fields to update record (check if using only nonempty fields in good practice first!)
        // 1. check if user already exists. if so, do update, else do insert
        $select = $this->tableGateway->getSql()->select()
            ->columns(['user_id']);
        $select->where->equalTo('barcode', $puraUser->getBarcode());
        $foundPuraUser = $this->tableGateway->selectWith($select)->current();

        if (count($foundPuraUser) == 0) {
            // create purauser (not needed so far, so no implementation yet)
        } elseif (count($foundPuraUser) == 1) {
            // update existing pura user with given, non-null, values
            $update = $this->tableGateway->getSql()->update();
            $fieldForPuraUserTable = [];

            if (!is_null($puraUser->getLibrarySystemNumber())) {
                $fieldForPuraUserTable['library_system_number']
                    = $puraUser->getLibrarySystemNumber();
            }
            if (!is_null($puraUser->getUserId())) {
                $fieldForPuraUserTable['user_id']
                    = $puraUser->getUserId();
            }
            if (!is_null($puraUser->getEduId())) {
                $fieldForPuraUserTable['edu_id']
                    = $puraUser->getEduId();
            }
            if (!is_null($puraUser->getRemarks())) {
                $fieldForPuraUserTable['remarks']
                    = $puraUser->getRemarks();
            }
            if (!is_null($puraUser->getDateExpiration())) {
                $fieldForPuraUserTable['date_expiration']
                    = $puraUser->getDateExpiration();
            }
            if ($puraUser->getAccessCreated()) {
                $fieldForPuraUserTable['access_created']
                    = $puraUser->getAccessCreated();
            }
            if (!is_null($puraUser->getBlocked())) {
                $fieldForPuraUserTable['blocked']
                    = $puraUser->getBlocked();
            }
            if (!is_null($puraUser->getBlockedCreated())) {
                $fieldForPuraUserTable['blocked_created']
                    = $puraUser->getBlockedCreated();
            }
            if (!is_null($puraUser->getCreated())) {
                $fieldForPuraUserTable['created']
                    = $puraUser->getCreated();
            }
            if (!is_null($puraUser->getHasAccess())) {
                $fieldForPuraUserTable['has_access']
                    = $puraUser->getHasAccess();
            }
            if (!is_null($puraUser->getLastAccountExtensionRequest())) {
                $fieldForPuraUserTable['last_account_extension_request']
                    = $puraUser->getLastAccountExtensionRequest();
            }
            if (!is_null($puraUser->getLibraryCode())) {
                $fieldForPuraUserTable['library_code']
                    = $puraUser->getLibraryCode();
            }
            if (!is_null($puraUser->getIsMemberEducationInstitution())) {
                $fieldForPuraUserTable['is_member_education_institution']
                    = $puraUser->getIsMemberEducationInstitution();
            }

            $update->set($fieldForPuraUserTable);
            $update->where->equalTo('barcode', $puraUser->getBarcode());
            $dbRetVal = $this->tableGateway->updateWith($update);

            return $dbRetVal;
        } else {
            throw new Exception('More than one record with the same unique key found in table "pura_user".');
        }

        return -1;
    }
}