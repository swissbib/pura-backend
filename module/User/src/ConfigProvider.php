<?php

namespace User;
use User\Form\LoginForm;
use User\Form\LoginFormFactory;
use User\Handler\LoginFactory;
use User\Handler\LoginHandler;
use User\Handler\LogoutHandler;
use User\InputFilter\LoginInputFilter;
use Zend\Expressive\Authentication\UserRepository\PdoDatabase;
use Zend\Expressive\Authentication\UserRepositoryInterface;
use Zend\ServiceManager\Factory\InvokableFactory;
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
                LoginHandler::class => LoginFactory::class,
                LogoutHandler::class => InvokableFactory::class,
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
                'user'    => [__DIR__ . '/../templates/user'],
            ],
        ];
    }

    private function getInputFilters()
    {
        return [
            'factories'  => [
                LoginInputFilter::class => InvokableFactory::class,
            ]
        ];
    }

    private function getFormElements()
    {
        return [
            'factories'  => [
                //LoginForm::class => LoginFormFactory::class,
            ]
        ];
    }
}
