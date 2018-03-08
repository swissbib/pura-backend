<?php

namespace PuraUser\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Publisher\BusinessLogicHelper\Publisher;
use PuraUserModel\Entity\PuraUserEntity;
use PuraUserModel\Repository\PuraUserRepository;
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

    private $switchConfig;

    private $puraUserRepository;

    private $puraUserList;

    /**
     * BarcodeEntryHandler constructor.
     * @param TemplateRendererInterface $template
     * @param Form $alephNrEntryForm
     * @param array $switchConfig
     * @param PuraUserRepository $puraUserRepository
     * @param array $puraUserList
     */
    public function __construct(
        TemplateRendererInterface $template,
        Form                      $alephNrEntryForm,
        array                     $switchConfig,
        PuraUserRepository        $puraUserRepository,
        array                     $puraUserList
    ) {
        $this->template             = $template;
        $this->alephNrEntryForm     = $alephNrEntryForm;
        $this->switchConfig         = $switchConfig;
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
        $puraUserEntity = $this->puraUserRepository->getSinglePuraUser($barcode);
        $libraryCode = $puraUserEntity->getLibraryCode();

        if ($request->getMethod() === 'POST') {
            $alephNr = $request->getParsedBody()['alephNrEntry'];
            // todo: filter (strip spaces) $alephNr here!

            $request = $request->withAttribute(
                'alephNrEntry',
                $alephNr
            );

            $retVal = 1;
            if ($puraUserEntity->getLibrarySystemNumber() !== $alephNr) {
                $retVal = $this->puraUserRepository->savePuraUserAlephNr($alephNr, $barcode);
            }

            if ($retVal > 0) {
                $publisherHelper = new Publisher($this->switchConfig, $this->puraUserRepository);
                $retVal = $publisherHelper->activatePublisher($puraUserEntity->getEduId(), $barcode, $libraryCode);
                if ($retVal['success']) {
                    $response = $handler->handle($request);
                    return new RedirectResponse('/purauser/edit/' . $puraUserEntity->getBarcode());
                } else {
                    $error = $retVal['message'];
                }
            } else {
                $error = 'There was an error saving the aleph number to the database.';
            }
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