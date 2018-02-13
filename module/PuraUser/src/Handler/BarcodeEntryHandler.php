<?php

namespace PuraUser\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Form\Form;

/**
 * BarcodeEntryHandler
 *
 * @category Swissbib_VuFind2
 * @package  ${PACKAGE}
 * @author   ${AUTHOR}
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 */
class BarcodeEntryHandler implements MiddlewareInterface
{
    /**
     * @var TemplateRendererInterface
     */
    private $template;
    /**
     * @var Form
     */
    private $barcodeEntryForm;

    private $x;

    /**
     * BarcodeEntryHandler constructor.
     * @param TemplateRendererInterface $template
     * @param Form $barcodeEntryForm
     */
    public function __construct(
        TemplateRendererInterface $template,
        Form                      $barcodeEntryForm,
        String                    $x
    ) {
        $this->template         = $template;
        $this->barcodeEntryForm = $barcodeEntryForm;
        $this->x = $x;
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
        /*
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        if ($session->has(UserInterface::class)) {
            return new RedirectResponse('/purauser/barcodeentry');
        }
        */

        $error = '';
        if ($request->getMethod() === 'POST') {
            //$inputFilter = $this->loginForm->getInputFilter();


            //$inputFilter->setData($request->getParsedBody());
            /*
            if ($inputFilter->isValid()) {
                $request = $request->withAttribute(
                    'username',
                    $inputFilter->getValue('username')
                );
                $request = $request->withAttribute(
                    'password',
                    $inputFilter->getValue('password')
                );

                $response = $handler->handle($request);
                if ($response->getStatusCode() !== 301) {
                    return new RedirectResponse('/');
                }

                $error = 'Login Failure, please try again';
            }
            */
            return new RedirectResponse('/purauser/alephnrentry');
        }

        return new HtmlResponse(
            $this->template->render(
                'purauser::barcodeentry-page', [
                      'barcodeEntryForm'  => $this->barcodeEntryForm,
                      'error' => $error,
                      'x' => $this->x
                ]
            )
        );
    }
}