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
use Zend\Stratigility\Middleware\ErrorHandler;

class AuthorizationMiddleware implements MiddlewareInterface
{

use UserTrait;

public function process(
    ServerRequestInterface $request,
    RequestHandlerInterface $handler
    ) : ResponseInterface
    {
        $session = $request->getAttribute(
        SessionMiddleware::SESSION_ATTRIBUTE
        );
        // no session
        // - set roles as "guest"
        // - when status code !== 403 or page = /login, return response
        // - otherwise, redirect to login page
        if (! $session->has(UserInterface::class)) {
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
                $response->getStatusCode() !== 403
            ) {
                return $response;
            }

            return new RedirectResponse('/login');
        }

        // has session but at /login page, redirect to home
        if ($request->getUri()->getPath() === '/login') {
            return RedirectResponse('/');
        }

        // define roles from DB
        $sessionData = $session->get(UserInterface::class);
        $request = $request->withAttribute(
            UserInterface::class,
            $this->generateUser(
                $sessionData['username'],
                $sessionData['roles']
            )
        );
        $response = $handler->handle($request);
        if($response->getStatusCode() == 403) {
            echo "Access to this page is restricted";
        }
        return $response;
    }
}