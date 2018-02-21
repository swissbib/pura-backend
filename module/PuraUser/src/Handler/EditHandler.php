<?php

namespace PuraUser\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Authentication\UserInterface;
use Zend\Expressive\Session\SessionMiddleware;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Form\Form;

/**
 * EditHandler
 *
 * @category Swissbib_VuFind2
 * @package  ${PACKAGE}
 * @author   ${AUTHOR}
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 */
class EditHandler implements MiddlewareInterface
{
    /**
     * @var TemplateRendererInterface
     */
    private $template;
    /**
     * @var Form
     */
    private $editForm;

    private $puraUserList;

    /**
     * EditHandler constructor.
     * @param TemplateRendererInterface $template
     * @param Form $barcodeEntryForm
     */
    public function __construct(
        TemplateRendererInterface $template,
        Form                      $editForm,
        array                     $puraUserList
    ) {
        $this->template         = $template;
        $this->editForm         = $editForm;
        $this->puraUserList     = $puraUserList;
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
        /*
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        if ($session->has(UserInterface::class)) {
            return new RedirectResponse('/purauser/alephnrentry');
        }
        */

        $error = '';
        if ($request->getMethod() === 'POST') {
            //$inputFilter = $this->editForm->getInputFilter();
            //$inputFilter->setData($request->getParsedBody());

            /*
            if ($inputFilter->isValid()) {
                $request = $request->withAttribute(
                    'editEntry',
                    $inputFilter->getValue('$editEntry')
                );

                $response = $handler->handle($request);
                if ($response->getStatusCode() !== 301) {
                    return new RedirectResponse('/purauser/edit');
                }

                $error = 'Input not accepted.';
            }
            */

        }

        return new HtmlResponse(
            $this->template->render(
                'purauser::edit-page', [
                      'editForm'  => $this->editForm,
                      'puraUserList' => $this->puraUserList,
                      'error' => $error,
                ]
            )
        );
    }
}