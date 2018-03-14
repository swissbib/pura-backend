<?php

namespace PuraUser\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use PuraUserModel\Repository\PuraUserRepository;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplateMapResolver;

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

    private $puraUserList;

    /** @var PuraUserRepository $puraUserRepository */
    private $puraUserRepository;

    /**
     * SearchPuraUserHandler constructor.
     * @param TemplateRendererInterface $template
     */
    public function __construct(
        TemplateRendererInterface $template,
        PuraUserRepository        $puraUserRepository
    )
    {
        $this->template = $template;
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
        $searchTerm = $request->getParsedBody()['sidebarSearchbox'];
        $filteredPuraUserList = $this->puraUserRepository->getFilteredListOfAllUsers($searchTerm);

        $renderer = new PhpRenderer();
        $model = new ViewModel(['puraUserList' => $filteredPuraUserList]);
        $resolver = new TemplateMapResolver([
            'search' => __DIR__ . '/../../templates/purauser/search.phtml',
        ]);
        $renderer->setResolver($resolver);
        $model->setTemplate('search');
        $response = $renderer->render($model);

        return new HtmlResponse($response);
    }
}
