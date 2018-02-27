<?php

namespace PuraUserModel\Repository;

use Interop\Container\ContainerInterface;
use PuraUserModel\Repository\PuraUserRepositoryInterface;
use PuraUserModel\Storage\PuraUserStorageInterface;

/**
 * Class PuraUserRepositoryFactory
 *
 * @package PuraUserModel\Repository
 */
class PuraUserRepositoryFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return PuraUserRepository
     */
    public function __invoke(ContainerInterface $container)
    {
        $puraUserStorage = $container->get(PuraUserStorageInterface::class);

        return new PuraUserRepository($puraUserStorage);
    }
}