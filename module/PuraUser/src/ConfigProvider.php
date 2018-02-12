<?php

namespace PuraUser;
use PuraUser\Form\BarcodeEntryForm;
use PuraUser\Form\BarcodeEntryFormFactory;
use PuraUser\Handler\BarcodeEntryHandler;
use PuraUser\Handler\BarcodeEntryFactory;
use Zend\Expressive\Authentication\UserRepository\PdoDatabase;
use Zend\Expressive\Authentication\UserRepositoryInterface;
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
                BarcodeEntryHandler::class => BarcodeEntryFactory::class,
                AuthenticationInterface::class => PhpSessionFactory::class,
            ],
            'aliases' => [
                // ...
                UserRepositoryInterface::class => PdoDatabase::class
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
                //LoginInputFilter::class => InvokableFactory::class,
            ]
        ];
    }

    private function getFormElements()
    {
        return [
            'factories'  => [
                BarcodeEntryForm::class => BarcodeEntryFormFactory::class,
            ]
        ];
    }
}
