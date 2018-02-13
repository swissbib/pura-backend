<?php

namespace PuraUser\Model\Storage\Db;

use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

/**
 * Class PuraUserDbStorageFactory
 *
 * @package PuraUser\Model\Storage\Db
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
        $adapter = $container->get(AdapterInterface::class);

        //$resultSet = new ResultSet(ResultSet::TYPE_ARRAY);
        $resultSet = new ResultSet();

        $dbConfig = $container->get('config')['db'];
        $adapter = new Adapter($dbConfig);

        $tableGateway = new TableGateway(
            'pura_user', $adapter, null, $resultSet
        );

        return new PuraUserDbStorage($tableGateway);
    }
}
