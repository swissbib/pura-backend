<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;

/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
return function (
                    Application $app,
                    MiddlewareFactory $factory,
                    ContainerInterface $container
                ) : void {
    /* Module 'App' */
    $app->get('/', App\Handler\HomePageHandler::class, 'home');


    /* Module 'User' */
    $app->route(
        '/login',
        [
            User\Handler\LoginHandler::class,
            \Zend\Expressive\Authentication\AuthenticationMiddleware::class
        ],
        ['GET', 'POST'],
        'user.login'
    );
    $app->get('/logout', User\Handler\LogoutHandler::class, 'user.logout');


    /* Module 'PuraUser' */
    $app->route(
        '/purauser/barcodeentry',
        PuraUser\Handler\BarcodeEntryHandler::class,
        ['GET', 'POST'],
        'purauser.barcodeentry'
    );
    $app->route(
        '/purauser/alephnrentry/:barcode',
        PuraUser\Handler\AlephNrEntryHandler::class,
        ['GET', 'POST'],
        'purauser.alephnrentry'
    );
    $app->route(
        '/purauser/edit',
        PuraUser\Handler\EditHandler::class,
        ['GET', 'POST'],
        'purauser.edit.emptyuser'
    );
    $app->route(
        '/purauser/edit/:barcode',
        PuraUser\Handler\EditHandler::class,
        ['GET', 'POST'],
        'purauser.edit'
    );
    $app->route(
        '/purauser/search',
        PuraUser\Handler\SearchPuraUserHandler::class,
        ['GET', 'POST'],
        'purauser.search'
    );
    $app->route(
        '/purauser/block/:barcode',
        PuraUser\Handler\BlockUserHandler::class,
        ['GET', 'POST'],
        'purauser.block'
    );


    /* Module 'Publisher' */
    $app->get('/publisher', Publisher\Handler\PublisherHandler::class, 'publisher');
    $app->post('/publisher/activate', Publisher\Handler\ActivatePublisherHandler::class, 'publisher.activate');
    $app->get('/publisher/deactivate', Publisher\Handler\DeactivatePublisherHandler::class, 'publisher.deactivate');
    $app->post('/publisher/deactivate', Publisher\Handler\DeactivatePublisherHandler::class, 'publisher.deactivate');
};
