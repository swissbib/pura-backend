<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\Helper\ServerUrlMiddleware;
use Zend\Expressive\Helper\UrlHelperMiddleware;
use Zend\Expressive\MiddlewareFactory;
use Zend\Expressive\Middleware\DispatchMiddleware;
use Zend\Expressive\Middleware\ImplicitHeadMiddleware;
use Zend\Expressive\Middleware\ImplicitOptionsMiddleware;
use Zend\Expressive\Middleware\NotFoundMiddleware;
use Zend\Expressive\Middleware\RouteMiddleware;
use Zend\Stratigility\Middleware\ErrorHandler;
use Zend\Expressive\Session\SessionMiddleware;
use Zend\Expressive\Flash\FlashMessageMiddleware;

/**
 * Setup middleware pipeline:
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    // The error handler should be the first (most outer) middleware to catch
    // all Exceptions.
    $app->pipe(ErrorHandler::class);
    $app->pipe(ServerUrlMiddleware::class);

    // Pipe more middleware here that you want to execute on every request:
    // - bootstrapping
    // - pre-conditions
    // - modifications to outgoing responses
    //
    // Piped Middleware may be either callables or service names. Middleware may
    // also be passed as an array; each item in the array must resolve to
    // middleware eventually (i.e., callable or service name).
    //
    // Middleware can be attached to specific paths, allowing you to mix and match
    // applications under a common domain.  The handlers in each middleware
    // attached this way will see a URI with the MATCHED PATH SEGMENT REMOVED!!!
    //
    // - $app->pipe('/api', $apiMiddleware);
    // - $app->pipe('/docs', $apiDocMiddleware);
    // - $app->pipe('/files', $filesMiddleware);

    // Register the routing middleware in the middleware pipeline
    $app->pipe(SessionMiddleware::class);
    $app->pipe(FlashMessageMiddleware::class);
    $app->pipe(RouteMiddleware::class);

    /*
    $app->pipe(new class implements Psr\Http\Server\MiddlewareInterface{

        use Zend\Expressive\Authentication\UserRepository\UserTrait;

        public function process(
            Psr\Http\Message\ServerRequestInterface $request,
            Psr\Http\Server\RequestHandlerInterface $handler
        ) : Psr\Http\Message\ResponseInterface {
            $session = $request->getAttribute(
                Zend\Expressive\Session\SessionMiddleware::SESSION_ATTRIBUTE
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
                    $request->getUri()->getPath() === '/logout' ||
                    $response->getStatusCode() !== 403
                ) {
                    return $response;
                }
                return new \Zend\Diactoros\Response\RedirectResponse('/login');
            }

            // has session but at /login page, redirect to authenticated page
            if ($request->getUri()->getPath() === '/login') {
                return new Zend\Diactoros\Response\RedirectResponse('/');
            }

            // define roles from DB
            $sessionData = $session->get(Zend\Expressive\Authentication\UserInterface::class);
            $request = $request->withAttribute(
                Zend\Expressive\Authentication\UserInterface::class,
                $this->generateUser(
                    $sessionData['username'],
                    $sessionData['library_code']
                )
            );
            return $handler->handle($request);
        }
    });
    $app->pipe(\Zend\Expressive\Authorization\AuthorizationMiddleware::class);
    */

    $app->pipe(ImplicitHeadMiddleware::class);
    $app->pipe(ImplicitOptionsMiddleware::class);
    $app->pipe(UrlHelperMiddleware::class);

    // Add more middleware here that needs to introspect the routing results; this
    // might include:
    //
    // - route-based authentication
    // - route-based validation
    // - etc.

    // Register the dispatch middleware in the middleware pipeline
    $app->pipe(DispatchMiddleware::class);

    // At this point, if no Response is returned by any middleware, the
    // NotFoundMiddleware kicks in; alternately, you can provide other fallback
    // middleware to execute.
    $app->pipe(NotFoundMiddleware::class);
};
