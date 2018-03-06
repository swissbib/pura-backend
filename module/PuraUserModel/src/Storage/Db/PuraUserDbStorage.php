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
    public function getBarcodeExists($barcode)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where->equalTo('barcode', $barcode);

        /** @var ResultSet $resultSet */
        $resultSet = $this->tableGateway->selectWith($select);
        if ($resultSet->count() < 1) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get a list of all PuraUsers
     *
     * @return array
     */
    public function getListOfAllUsers()
    {
        return $this->getFilteredListOfAllUsers('');
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
        $select->columns(['user_id','edu_id','barcode', 'access_created', 'date_expiration', 'remarks', 'library_system_number']);
        $select->where->equalTo('barcode', $barcode);
        $select->join('user', 'user.id = pura_user.user_id', ['firstname', 'lastname', 'email'], 'left');

        /** @var ResultSet $resultSet */
        $resultSet = $this->tableGateway->selectWith($select);
        if ($resultSet->count() < 1) return false;
        $puraUserEntity = $this->createPuraUserEntity($resultSet->current());

        return $puraUserEntity;
    }

    /**
     * Get a filtered lsit of all PuraUsers

     * @param $filter
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
            ->unnest()
            ->and->like('pura_user.library_code', 'Z01');
        $select->join('user', 'user.id = pura_user.user_id', ['firstname','lastname'], 'left');

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
            $fieldForUserTable = [];

            if (!is_null($puraUser->getLibrarySystemNumber())) {
                $fieldForPuraUserTable['library_system_number']
                    = $puraUser->getLibrarySystemNumber();
            }
            if (!is_null($puraUser->getUserId())) {
                $fieldForPuraUserTable['user_id']
                    = $puraUser->getUserId();
            }
            if (!is_null($puraUser->getEmail())) {
                $fieldForUserTable['email']
                    = $puraUser->getEmail();
            }
            if (!is_null($puraUser->getEduId())) {
                $fieldForPuraUserTable['edu_id']
                    = $puraUser->getEduId();
            }
            if (!is_null($puraUser->getFirstname())) {
                $fieldForUserTable['firstname']
                    = $puraUser->getFirstname();
            }
            if (!is_null($puraUser->getLastname())) {
                $fieldForUserTable['lastname']
                    = $puraUser->getLastname();
            }
            if (!is_null($puraUser->getRemarks())) {
                $fieldForPuraUserTable['remarks']
                    = $puraUser->getRemarks();
            }
            if (!is_null($puraUser->getDateExpiration())) {
                $fieldForPuraUserTable['date_expiration']
                    = $puraUser->getDateExpiration();
            }
            if (!is_null($puraUser->getAccessCreated())) {
                $fieldForPuraUserTable['access_created']
                    = $puraUser->getAccessCreated();
            }
            if (!is_null($puraUser->getLanguage())) {
                $fieldForUserTable['language']
                    = $puraUser->getLanguage();
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
            if (!is_null($puraUser->getUsername())) {
                $fieldForUserTable['username']
                    = $puraUser->getUsername();
            }

            $update->set($fieldForPuraUserTable);
            $update->where->equalTo('barcode', $puraUser->getBarcode());
            $dbRetVal = $this->tableGateway->updateWith($update);

            //todo: consider updating values from table 'user' as well - or don't.

            return $dbRetVal;
        } else {
            throw new Exception('More than one record with the same unique key found in table "pura_user".');
        }

        return -1;
    }
}