<?php

namespace PuraUser\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use PuraUser\Model\Repository\PuraUserRepository;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Form\Form;
use Zend\View\Model\ViewModel;

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

    /** @var PuraUserRepository $puraUserRepository */
    private $puraUserRepository;

    /**
     * BarcodeEntryHandler constructor.
     * @param TemplateRendererInterface $template
     * @param Form $barcodeEntryForm
     */
    public function __construct(
        TemplateRendererInterface $template,
        Form                      $barcodeEntryForm,
        PuraUserRepository        $puraUserRepository
        //array                     $puraUserList
    )
    {
        $this->template = $template;
        $this->barcodeEntryForm = $barcodeEntryForm;
        $this->puraUserRepository = $puraUserRepository;
        //$this->puraUserList = $puraUserList;
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
        $searchTerm = $request->getAttribute('sidebarSearchbox');
        $filteredPuraUserList = $this->puraUserRepository->getFilteredListOfAllUsers($searchTerm);

        $viewModel = new ViewModel([ 'puraUserList' => $filteredPuraUserList]);
        $viewModel->setTerminal(true);
        $viewModel->setTemplate('purauser::sidebar');

        return $viewModel;
    }
}
