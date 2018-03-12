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
class BlockUserHandler implements MiddlewareInterface
{
    /**
     * @var TemplateRendererInterface
     */
    private $template;

    private $switchConfig;

    private $puraUserRepository;

    private $puraUserList;

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
        PuraUserRepository        $puraUserRepository,
        array                     $puraUserList
    ) {
        $this->template             = $template;
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
        $message = '';
        $barcode = $request->getAttribute('barcode');

        /** @var PuraUserEntity $puraUserEntity */
        $puraUserEntity = $this->puraUserRepository->getSinglePuraUser($barcode);
        $libraryCode = $puraUserEntity->getLibraryCode();



        $publisherHelper = new Publisher($this->switchConfig, $this->puraUserRepository);
        $retVal = $publisherHelper->deactivatePublishers($puraUserEntity->getEduId(), $barcode, $libraryCode);
        $message = $retVal['message'];
        if ($retVal['success']) {
            $flashMessages = $request->getAttribute(FlashMessageMiddleware::FLASH_ATTRIBUTE);
            $flashMessages->flash('message', $retVal['message']);
            $response = $handler->handle($request);
            return new RedirectResponse('/purauser/edit/' . $puraUserEntity->getBarcode());
        } else {
            $message = 'There was an error blocking the user.';
        }
    }
}