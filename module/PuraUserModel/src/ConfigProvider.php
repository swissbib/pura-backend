<?php

namespace PuraUserModel;

use PuraUserModel\Repository\PuraUserRepositoryFactory;
use PuraUserModel\Repository\PuraUserRepositoryInterface;
use PuraUserModel\Storage\Db\PuraUserDbStorageFactory;
use PuraUserModel\Storage\PuraUserStorageInterface;

/**
 * The configuration provider for the PuraUserModel module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'factories'  => [
                PuraUserStorageInterface::class => PuraUserDbStorageFactory::class,
                PuraUserRepositoryInterface::class => PuraUserRepositoryFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
            ],
        ];
    }
}