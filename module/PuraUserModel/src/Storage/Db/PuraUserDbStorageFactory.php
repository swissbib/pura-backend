<?php

namespace PuraUserModel\Storage\Db;

use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

/**
 * Class PuraUserDbStorageFactory
 *
 * @package PuraUserModel\Storage\Db
 */
class PuraUserDbStorageFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return PuraUserDbStorage
     */
    public function __invoke(ContainerInterface $container)
    {
        $resultSet = new ResultSet();
        $dbConfig = $container->get('config')['db'];
        $adapter = new Adapter($dbConfig);

        $tableGateway = new TableGateway(
            'pura_user', $adapter, null, $resultSet
        );

        return new PuraUserDbStorage($tableGateway);
    }
}
