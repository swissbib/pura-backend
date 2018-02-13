<?php

namespace PuraUser\Model\Storage\Db;

use PuraUser\Model\Storage\PuraUserStorageInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
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
        $select = $this->tableGateway->getSql()->select();
        $data = [];

        foreach ($this->tableGateway->selectWith($select) as $row) {
            $data[] = $row;
        }

        return $data;
    }

    /**
     * Get PuraUser by barcode
     *
     * @param integer $barcode
     *
     * @return array
     */
    public function getSingleUser($barcode)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where->equalTo('barcode', $barcode);

        /** @var ResultSet $resultSet */
        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet->current();
    }

}