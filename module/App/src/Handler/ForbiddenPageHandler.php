<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Authentication\UserInterface;
use Zend\Expressive\Session\SessionMiddleware;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * HomePageHandler
 *
 * @category Swissbib_VuFind2
 * @package  ${PACKAGE}
 * @author   ${AUTHOR}
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 * @link     http://www.swissbib.ch
 */
class ForbiddenPageHandler implements RequestHandlerInterface
{

    /** @var TemplateRendererInterface  */
    private $template;

    /**
     * HomePageHandler constructor.
     * @param TemplateRendererInterface|null $template
     */
    public function __construct(TemplateRendererInterface $template = null)
    {
        $this->template = $template;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return new HtmlResponse(
            $this->template->render('error::403')
        );
    }
}
