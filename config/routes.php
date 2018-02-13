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
    $app->get('/', App\Handler\HomePageHandler::class, 'home');
    $app->get('/api/ping', App\Handler\PingHandler::class, 'api.ping');
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
    $app->get(
        '/purauser/barcodeentry',
        PuraUser\Handler\BarcodeEntryHandler::class,
        'purauser.barcodeentry'
    );
    $app->get(
        '/purauser/alephnrentry',
        PuraUser\Handler\BarcodeEntryHandler::class,
        'purauser.alephnrentry'
    );
    $app->get('/publisher', Publisher\Handler\PublisherHandler::class, 'publisher');
    $app->get('/publisher/activate', Publisher\Handler\ActivatePublisherHandler::class, 'publisher.activate');
    $app->get('/publisher/deactivate', Publisher\Handler\DeactivatePublisherHandler::class, 'publisher.deactivate');
};
