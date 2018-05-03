<?php

namespace Publisher;

use Publisher\Handler\ReloadUsersFactory;
use Publisher\Handler\ReloadUsersHandler;
use Publisher\Handler\DeactivatePublisherFactory;
use Publisher\Handler\DeactivatePublisherHandler;
use Publisher\Handler\PublisherFactory;
use Publisher\Handler\PublisherHandler;
use Zend\ServiceManager\Factory\InvokableFactory;

/**
 * The configuration provider for the Publisher  module
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
                PublisherHandler::class => PublisherFactory::class,
                ReloadUsersHandler::class => ReloadUsersFactory::class,
                DeactivatePublisherHandler::class => DeactivatePublisherFactory::class,
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
                'publisher' => [__DIR__ . '/../templates/publisher'],
            ],
        ];
    }
}