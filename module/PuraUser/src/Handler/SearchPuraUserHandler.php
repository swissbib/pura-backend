<?php

namespace PuraUser\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use PuraUser\Model\Repository\PuraUserRepository;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Form\Form;

/**
 * SearchPuraUserHandler
 *
 * @category Swissbib_VuFind2
 * @package  ${PACKAGE}
 * @author   ${AUTHOR}
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 */
class SearchPuraUserHandler implements MiddlewareInterface
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
        array                     $puraUserList,
        PuraUserRepository        $puraUserRepository
    )
    {
        $this->template = $template;
        $this->barcodeEntryForm = $barcodeEntryForm;
        $this->puraUserList = $puraUserList;
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
        $userId = $request->getAttribute('user_id');

        // call find()/getfiltereduserlist form purauserrepository.php

        // return viewmodel(?) containing the passed userlist
    }
}
