<?php

namespace PuraUser;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Authentication\UserInterface;
use Zend\Expressive\Authentication\UserRepository\UserTrait;
use Zend\Expressive\Session\SessionMiddleware;

class AuthorizationMiddleware implements MiddlewareInterface
{

use UserTrait;

public function process(
    ServerRequestInterface $request,
    RequestHandlerInterface $handler
    ) : ResponseInterface {
        $session = $request->getAttribute(
        SessionMiddleware::SESSION_ATTRIBUTE
        );

        // no session
        // - set roles as "guest"
        // - when status code !== 403 or page = /login, return response
        // - otherwise, redirect to login page
         //if (! $session->has(UserInterface::class)) {
         if (! $request->getAttribute('Zend\\Expressive\\Authentication\\UserInterface')) {
            $user = '';
            $roles = ['default'];

            $request = $request->withAttribute(
                UserInterface::class,
                $this->generateUser(
                    $user,
                    $roles
                )
            );

            $response = $handler->handle($request);
            if ($request->getUri()->getPath() === '/login' ||
                $request->getUri()->getPath() === '/logout' ||
                $request->getUri()->getPath() === '/' ||
                $response->getStatusCode() !== 401 &&
                $response->getStatusCode() !== 403
            ) {
                return $response;
            }
            return new \Zend\Diactoros\Response\RedirectResponse('/login');
        }

        // has session but at /login page, redirect to authenticated page
        if ($request->getUri()->getPath() === '/login' ||
            $request->getUri()->getPath() === '/'
        ) {
            return new RedirectResponse('/purauser/barcodeentry');
        }

        // define roles from DB
        //$sessionData = $session->get(UserInterface::class);
        $sessionData = $request->getAttribute('Zend\\Expressive\\Authentication\\UserInterface');
        $request = $request->withAttribute(
            UserInterface::class,
            $this->generateUser(
                $sessionData['username'],
                $sessionData['library_code']
            )
        );
        return $handler->handle($request);
    }
}