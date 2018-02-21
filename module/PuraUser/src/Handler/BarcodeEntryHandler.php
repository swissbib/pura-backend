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

    private $puraUserList;

    /**
     * BarcodeEntryHandler constructor.
     * @param TemplateRendererInterface $template
     * @param Form $barcodeEntryForm
     */
    public function __construct(
        TemplateRendererInterface $template,
        Form                      $barcodeEntryForm,
        array                     $puraUserList
    ) {
        $this->template         = $template;
        $this->barcodeEntryForm = $barcodeEntryForm;
        $this->puraUserList     = $puraUserList;
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
        //$session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        //if ($session->has(UserInterface::class)) {
            //return new RedirectResponse('/purauser/barcodeentry');
        //}

        $error = '';
        if ($request->getMethod() === 'POST') {
            $barcodeEntry = $request->getParsedBody()['barcodeEntry'];

            $barcodeEntryValidator = new \Zend\Validator\Regex(['pattern' => '/^[A-Z0-9]+$/']); // allow only capital letters and/or numbers, but multiple of them
            $isValid = $barcodeEntryValidator->isValid($barcodeEntry);

            if ($isValid) {
                // consider using striptags'n'trim-filter here an then add it as a derived request attibute!
                $response = $handler->handle($request);
                if ($response->getStatusCode() !== 301) {
                    return new RedirectResponse('/purauser/alephnrentry/' . $barcodeEntry);
                }

            }
            $error = 'Barcode not accepted.';
        }

        return new HtmlResponse(
            $this->template->render(
                'purauser::barcodeentry-page', [
                      'barcodeEntryForm'  => $this->barcodeEntryForm,
                      'puraUserList' => $this->puraUserList,
                      'error' => $error,
                ]
            )
        );
    }
}