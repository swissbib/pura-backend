<?php

namespace PuraUser\Model\Repository;

use Interop\Container\ContainerInterface;
use PuraUser\Model\Repository\PuraUserRepositoryInterface;
use PuraUser\Model\Storage\PuraUserStorageInterface;

/**
 * Class PuraUserRepositoryFactory
 *
 * @package PuraUser\Model\Repository
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