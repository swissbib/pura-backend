<?php

namespace PuraUser\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use PuraUserModel\Repository\PuraUserRepository;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Filter\StaticFilter;
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

    private $puraUserList;

    private $puraUserRepository;

    /**
     * BarcodeEntryHandler constructor.
     * @param TemplateRendererInterface $template
     */
    public function __construct(
        TemplateRendererInterface $template,
        array                     $puraUserList,
        PuraUserRepository        $puraUserRepository
    ) {
        $this->template           = $template;
        $this->puraUserList       = $puraUserList;
        $this->puraUserRepository = $puraUserRepository;
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
        $message = '';
        if ($request->getMethod() === 'POST') {
            $barcode = $request->getParsedBody()['barcodeEntry'];

            // filter input:
            $barcode = StaticFilter::execute($barcode, 'StripTags');
            $barcode = StaticFilter::execute($barcode, 'StringTrim');

            // validate input: (allow only capital letters and/or numbers, but multiple of them)
            $barcodeEntryValidator = new \Zend\Validator\Regex(['pattern' => '/^[A-Z0-9]+$/']);
            $isValid = $barcodeEntryValidator->isValid($barcode);
            $message = 'Barcode is in an invalid format.';

            // validate against DB:
            $isValid = $this->getBarcodeExistInDb($barcode);
            if (!$isValid) $message = 'Barcode does not exist in the database.';

            if ($isValid) {
                $request = $request->withAttribute('barcodeEntry', $barcode);
                $response = $handler->handle($request);
                if ($response->getStatusCode() !== 301) {
                    return new RedirectResponse('/purauser/alephnrentry/' . $barcode);
                }
            }
        }

        return new HtmlResponse(
            $this->template->render(
                'purauser::barcodeentry-page', [
                      'puraUserList' => $this->puraUserList,
                      'message' => $message,
                ]
            )
        );
    }

    private function getBarcodeExistInDb($barcode)
    {
        return $this->puraUserRepository->getBarcodeExists($barcode);
    }

}