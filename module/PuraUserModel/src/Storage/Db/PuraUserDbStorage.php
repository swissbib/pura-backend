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
     * Get a list of all PuraUsers
     *
     * @return array
     */
    public function getListOfAllUsers()
    {
        return $this->getFilteredListOfAllUsers('');
    }

    /**
     * Get PuraUser by barcode
     *
     * @param integer $barcode
     *
     * @return array
     */
    public function getSinglePuraUserByBarcode($barcode)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['user_id','edu_id','barcode', 'access_created', 'date_expiration', 'remarks', 'library_system_number']);
        $select->where->equalTo('barcode', $barcode);
        $select->join('user', 'user.id = pura_user.user_id', ['firstname', 'lastname', 'email'], 'left');

        /** @var ResultSet $resultSet */
        $resultSet = $this->tableGateway->selectWith($select);
        $puraUserArray = $resultSet->current();

        $puraUserEntity = new PuraUserEntity();
        $puraUserEntity->setUserId($puraUserArray['user_id']);
        $puraUserEntity->setEduId($puraUserArray['edu_id']);
        $puraUserEntity->setBarcode($puraUserArray['barcode']);
        $puraUserEntity->setAccessCreated($puraUserArray['access_created']);
        $puraUserEntity->setDateExpiration($puraUserArray['date_expiration']);
        $puraUserEntity->setRemarks($puraUserArray['remarks']);
        $puraUserEntity->setLibrarySystemNumber($puraUserArray['library_system_number']);
        $puraUserEntity->setFirstname($puraUserArray['firstname']);
        $puraUserEntity->setLastname($puraUserArray['lastname']);
        $puraUserEntity->setEmail($puraUserArray['email']);

        return $puraUserEntity;
    }

    /**
     * Get PuraUser by user_id
     *
     * @param integer $userId
     *
     * @return PuraUserEntity
     */
    public function getSinglePuraUserByUserId($userId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['user_id','edu_id','barcode', 'access_created', 'date_expiration', 'remarks', 'library_system_number']);
        $select->where->equalTo('user_id', $userId);
        $select->join('user', 'user.id = pura_user.user_id', ['firstname', 'lastname', 'email'], 'left');

        /** @var ResultSet $resultSet */
        $resultSet = $this->tableGateway->selectWith($select);
        $puraUserArray = $resultSet->current();

        $puraUserEntity = new PuraUserEntity();
        $puraUserEntity->setUserId($puraUserArray['user_id']);
        $puraUserEntity->setEduId($puraUserArray['edu_id']);
        $puraUserEntity->setBarcode($puraUserArray['barcode']);
        $puraUserEntity->setAccessCreated($puraUserArray['access_created']);
        $puraUserEntity->setDateExpiration($puraUserArray['date_expiration']);
        $puraUserEntity->setRemarks($puraUserArray['remarks']);
        $puraUserEntity->setLibrarySystemNumber($puraUserArray['library_system_number']);
        $puraUserEntity->setFirstname($puraUserArray['firstname']);
        $puraUserEntity->setLastname($puraUserArray['lastname']);
        $puraUserEntity->setEmail($puraUserArray['email']);

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
        $select->where->like('edu_id', $filter)
            ->where->or->like('user_id', $filter)
            ->where->or->like('barcode', $filter);
        $select->join('user', 'user.id = pura_user.user_id', ['firstname','lastname'], 'left');

        $puraUserEntityArray = [];
        $puraUserEntity = new PuraUserEntity();

        foreach ($this->tableGateway->selectWith($select) as $row) {
            $puraUserArray = $row;
            $puraUserEntity->setUserId($puraUserArray['user_id']);
            $puraUserEntity->setEduId($puraUserArray['edu_id']);
            $puraUserEntity->setBarcode($puraUserArray['barcode']);
            $puraUserEntity->setAccessCreated($puraUserArray['access_created']);
            $puraUserEntity->setDateExpiration($puraUserArray['date_expiration']);
            $puraUserEntity->setRemarks($puraUserArray['remarks']);
            $puraUserEntity->setLibrarySystemNumber($puraUserArray['library_system_number']);
            $puraUserEntity->setFirstname($puraUserArray['firstname']);
            $puraUserEntity->setLastname($puraUserArray['lastname']);
            $puraUserEntity->setEmail($puraUserArray['email']);
            $puraUserEntityArray[] = $puraUserEntity;
        }
        return $puraUserEntityArray;
    }

    public function savePuraUserAlephNrIdentifiedByBarcode($alephNr, $barcode)
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
        // read entity and use userId as primary. use non-empty fields OR all fields to update record (check if using only nonempty fields in good practice first!)
        // 1. check if user already exists. if so, do update, else do insert
        $select = $this->tableGateway->getSql()->select()
            ->columns(['user_id']);
        $select->where->equalTo('user_id', $puraUser->getUserId());
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
            if (!is_null($puraUser->getBarcode())) {
                $fieldForPuraUserTable['barcode']
                    = $puraUser->getBarcode();
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
            $update->where->equalTo('user_id', $puraUser->getUserId());
            $dbRetVal = $this->tableGateway->updateWith($update);

            //todo: consider updating values from table 'user' as well - or don't.

            return $dbRetVal;
        } else {
            throw new Exception('More than one record with the same primary key found in table "pura_user".');
        }

        return -1;
    }
}