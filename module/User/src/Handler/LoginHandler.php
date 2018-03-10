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
use Zend\Filter\StaticFilter;

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
     * LoginHandler constructor.
     * @param TemplateRendererInterface $template
     */
    public function __construct(
        TemplateRendererInterface $template
    ) {
        $this->template  = $template;
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

        $message = '';
        if ($request->getMethod() === 'POST') {
            $username = $request->getParsedBody()['username'];
            $password = $request->getParsedBody()['password'];

            // filter:
            $username = StaticFilter::execute($username, 'StringTrim');
            $username = StaticFilter::execute($username, 'StripTags');
            $password = StaticFilter::execute($password, 'StringTrim');
            $password = StaticFilter::execute($password, 'StripTags');

            $request = $request->withAttribute('username', $username);
            $request = $request->withAttribute('password', $password);

            $response = $handler->handle($request);
            if ($response->getStatusCode() !== 301) {
                return new RedirectResponse('/purauser/barcodeentry');
            }
            $message = 'Login Failure, please try again';
        }

        return new HtmlResponse(
            $this->template->render('user::login-page', [
                'message' => $message,
            ])
        );
    }
}