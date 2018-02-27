<?php

namespace PuraUser\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use PuraUserModel\Entity\PuraUserEntity;
use PuraUserModel\Repository\PuraUserRepository;
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

        /** @var PuraUserEntity $puraUserEntity */
        $puraUserEntity = $this->puraUserRepository->getSinglePuraUserByBarcode($barcode);

        if ($request->getMethod() === 'POST') {
            $alephNr = $request->getParsedBody()['alephNrEntry'];
            // todo: filter (strip spaces) $alephNr here!

            $request = $request->withAttribute(
                'alephNrEntry',
                $alephNr
            );

            $dbReturnCode = 1;
            if ($puraUserEntity->getLibrarySystemNumber() !== $alephNr) {
                $dbReturnCode = $this->puraUserRepository->savePuraUserAlephNrIdentifiedByBarcode($alephNr, $barcode);
            }

            if ($dbReturnCode == 1) {
                $response = $handler->handle($request);
                return new RedirectResponse('/purauser/edit/' . $puraUserEntity->getUserId());
            }
            $error = 'There was an error saving the aleph number to the database.';
        }

        return new HtmlResponse(
            $this->template->render(
                'purauser::alephnrentry-page', [
                      'alephNrEntryForm'  => $this->alephNrEntryForm,
                      'puraUserList' => $this->puraUserList,
                      'puraUserEntity' => $puraUserEntity,
                      'error' => $error
                ]
            )
        );
    }
}