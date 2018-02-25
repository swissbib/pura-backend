<?php

namespace PuraUser\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use PuraUser\Model\Repository\PuraUserRepository;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Authentication\UserInterface;
use Zend\Expressive\Session\SessionMiddleware;
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
class AlephNrEntryHandler implements MiddlewareInterface
{
    /**
     * @var TemplateRendererInterface
     */
    private $template;
    /**
     * @var Form
     */
    private $alephNrEntryForm;

    private $puraUserRepository;

    private $puraUserList;

    /**
     * BarcodeEntryHandler constructor.
     * @param TemplateRendererInterface $template
     * @param Form $barcodeEntryForm
     */
    public function __construct(
        TemplateRendererInterface $template,
        Form                      $alephNrEntryForm,
        PuraUserRepository        $puraUserRepository,
        array                     $puraUserList
    ) {
        $this->template             = $template;
        $this->alephNrEntryForm     = $alephNrEntryForm;
        $this->puraUserRepository   = $puraUserRepository;
        $this->puraUserList         = $puraUserList;
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
        $error = '';
        $barcode = $request->getAttribute('barcode');

        if ($request->getMethod() === 'POST') {
            $alephNr = $request->getParsedBody()['alephNrEntry'];
            // todo: filter (strip spaces) $alephNr here!
            $puraUserId = $this->puraUserRepository->savePuraUserAlephNrIdentifiedByBarcode($alephNr, $barcode);

            $request = $request->withAttribute(
                'alephNrEntry',
                $alephNr
            );

            $response = $handler->handle($request);
            if ($response->getStatusCode() !== 301) {
                return new RedirectResponse('/purauser/edit/' . $puraUserId);
            }
            $error = 'Aleph Number not accepted.';
        }

        $singlePuraUserRecord = $this->puraUserRepository->getSinglePuraUser($barcode);

        // todo: return entity instead of $singlePuraUserRecord-array
        return new HtmlResponse(
            $this->template->render(
                'purauser::alephnrentry-page', [
                      'alephNrEntryForm'  => $this->alephNrEntryForm,
                      'puraUserList' => $this->puraUserList,
                      'singlePuraUserRecord' => $singlePuraUserRecord,
                      'error' => $error
                ]
            )
        );
    }
}