<?php

namespace PuraUser\Model\Storage\Db;

use PuraUser\Model\Storage\PuraUserStorageInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\TableGatewayInterface;

/**
 * Class PuraUserDbStorage
 *
 * @package PuraUser\Model\Storage\Db
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
    public function getSinglePuraUser($barcode)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['user_id','edu_id','barcode', 'access_created', 'date_expiration', 'remarks', 'library_system_number']);
        $select->where->equalTo('barcode', $barcode);
        $select->join('user', 'user.id = pura_user.user_id', ['firstname', 'lastname', 'email'], 'left');

        /** @var ResultSet $resultSet */
        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet->current();
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
        $data = [];

        foreach ($this->tableGateway->selectWith($select) as $row) {
            $data[] = $row;
        }
        return $data;
    }

}