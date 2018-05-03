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
use Zend\Expressive\Flash\FlashMessageMiddleware;
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
class AlephNrEntryHandler implements MiddlewareInterface
{
    /**
     * @var TemplateRendererInterface
     */
    private $template;

    private $switchConfig;

    private $puraUserRepository;

    /**
     * BarcodeEntryHandler constructor.
     * @param TemplateRendererInterface $template
     * @param array $switchConfig
     * @param PuraUserRepository $puraUserRepository
     * @param array $puraUserList
     */
    public function __construct(
        TemplateRendererInterface $template,
        array                     $switchConfig,
        PuraUserRepository        $puraUserRepository
    ) {
        $this->template             = $template;
        $this->switchConfig         = $switchConfig;
        $this->puraUserRepository   = $puraUserRepository;
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
        $barcode = $request->getAttribute('barcode');

        /** @var PuraUserEntity $puraUserEntity */
        $puraUserEntity = $this->puraUserRepository->getSinglePuraUser($barcode);
        $libraryCode = $puraUserEntity->getLibraryCode();

        if ($request->getMethod() === 'POST') {
            $alephNr = $request->getParsedBody()['alephNrEntry'];
            $alephNr = StaticFilter::execute($alephNr, 'StringTrim');
            $alephNr = StaticFilter::execute($alephNr, 'StripTags');

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
                $retVal = $publisherHelper->activatePublishers($puraUserEntity->getEduId(), $barcode, $libraryCode);

                $message = $retVal['message'];
                if ($retVal['success']) {
                    $flashMessages = $request->getAttribute(FlashMessageMiddleware::FLASH_ATTRIBUTE);
                    $flashMessages->flash('message', $retVal['message']);

                    if (!$puraUserEntity->getHasAccess()) {
                        //we record the first date where the account was created, not the renewals
                        $puraUserEntity->setAccessCreated(date("Y-m-d H:i:s"));
                    }
                    $puraUserEntity->setHasAccess(true);

                    $dateExpiration = date('Y-m-d H:i:s', strtotime('+1 year'));
                    $puraUserEntity->setDateExpiration($dateExpiration);

                    $this->puraUserRepository->savePuraUser($puraUserEntity);

                    /* unblock user, will set the blocking date
to null, which is not possible with setter methods */
                    $this->puraUserRepository->unBlockUser($barcode);

                    $response = $handler->handle($request);

                    return new RedirectResponse('/purauser/edit/' . $puraUserEntity->getBarcode());
                } else {
                }
            } else {
                $message .= 'There was an error saving the aleph number to the database.';
            }
        }

        if ($puraUserEntity->getBlocked()) {
            $message .= 'This user is blocked, please make sure you want to activate this user again. ';
        }
        return new HtmlResponse(
            $this->template->render(
                'purauser::alephnrentry-page', [
                      'puraUserEntity' => $puraUserEntity,
                      'message' => $message
                ]
            )
        );
    }
}