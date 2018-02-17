<?php

namespace User\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Authentication\UserInterface;
use Zend\Expressive\Session\SessionMiddleware;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Form\Form;

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
     * @var Form
     */
    private $loginForm;

    /**
     * LoginHandler constructor.
     * @param TemplateRendererInterface $template
     * @param Form $loginForm
     */
    public function __construct(
        TemplateRendererInterface $template,
        Form                      $loginForm
    ) {
        $this->template  = $template;
        $this->loginForm = $loginForm;
    }

    /**
     * Process an incoming server request and return a response,
     * optionally delegating response creation to a handler.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        if ($session->has(UserInterface::class)) {
            return new RedirectResponse('/login');
        }

        $error = '';
        if ($request->getMethod() === 'POST') {
            $inputFilter = $this->loginForm->getInputFilter();


            $inputFilter->setData($request->getParsedBody());
            if ($inputFilter->isValid()) {
                $request = $request->withAttribute('username', $inputFilter->getValue('username'));
                $request = $request->withAttribute('password', $inputFilter->getValue('password'));

                $response = $handler->handle($request);
                if ($response->getStatusCode() !== 301) {
                    return new RedirectResponse('/purauser/barcodeentry');
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