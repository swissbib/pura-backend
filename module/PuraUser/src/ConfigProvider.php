<?php

namespace PuraUser;
use PuraUser\Handler\AlephNrEntryFactory;
use PuraUser\Handler\AlephNrEntryHandler;
use PuraUser\Handler\BarcodeEntryHandler;
use PuraUser\Handler\BarcodeEntryFactory;
use PuraUser\Handler\BlockUserFactory;
use PuraUser\Handler\BlockUserHandler;
use PuraUser\Handler\EditFactory;
use PuraUser\Handler\EditHandler;
use PuraUser\Handler\SearchPuraUserHandler;
use PuraUser\Handler\SearchPuraUserHandlerFactory;
use Zend\Expressive\Authentication\AuthenticationInterface;
use Zend\Expressive\Authentication\Session\PhpSessionFactory;

/**
 * The configuration provider for the User module
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
            'dependencies'  => $this->getDependencies(),
            'templates'     => $this->getTemplates(),
            'input_filters' => $this->getInputFilters(),
            'form_elements' => $this->getFormElements(),
        ];
    }

    /**
     * Returns the container dependencies
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            'factories'  => [
                SearchPuraUserHandler::class => SearchPuraUserHandlerFactory::class,
                EditHandler::class => EditFactory::class,
                AlephNrEntryHandler::class => AlephNrEntryFactory::class,
                BarcodeEntryHandler::class => BarcodeEntryFactory::class,
                AuthenticationInterface::class => PhpSessionFactory::class,
                BlockUserHandler::class => BlockUserFactory::class,
            ],
            'aliases' => [
            ],
        ];
    }

    /**
     * Returns the templates configuration
     *
     * @return array
     */
    public function getTemplates()
    {
        return [
            'paths' => [
                'purauser'    => [__DIR__ . '/../templates/purauser'],
            ],
        ];
    }

    private function getInputFilters()
    {
        return [
            'factories'  => [
            ]
        ];
    }

    private function getFormElements()
    {
        return [
            'factories'  => [
                //BarcodeEntryForm::class => BarcodeEntryFormFactory::class,
            ]
        ];
    }
}
