<?php

namespace User\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use User\Form\LoginForm;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Authentication\UserInterface;
use Zend\Expressive\Session\SessionMiddleware;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * LoginHandler
 *
 * @category Swissbib_VuFind2
 * @package  ${PACKAGE}
 * @author   ${AUTHOR}
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 */
class LoginHandler implements MiddlewareInterface
{
    /**
     * @var TemplateRendererInterface
     */
    private $template;
    /**
     * @var LoginForm
     */
    private $loginForm;

    /**
     * LoginHandler constructor.
     * @param TemplateRendererInterface $template
     * @param LoginForm $loginForm
     */
    public function __construct(
        TemplateRendererInterface $template,
        LoginForm                 $loginForm
    ) {
        $this->template  = $template;
        $this->loginForm = $loginForm;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        if ($session->has(UserInterface::class)) {
            return new RedirectResponse('/');
        }

        $error = '';
        if ($request->getMethod() === 'POST') {
            $this->loginForm->setData($request->getParsedBody());
            if ($this->loginForm->isValid()) {
                $response = $handler->handle($request);
                if ($response->getStatusCode() !== 301) {
                    return new RedirectResponse('/');
                }

                $error = 'Login Failure, please try again';
            }
        }

        return new HtmlResponse(
            $this->template->render('user::login-page', [
                'loginForm'  => $this->loginForm,
                'error' => $error,
            ])
        );
    }
}