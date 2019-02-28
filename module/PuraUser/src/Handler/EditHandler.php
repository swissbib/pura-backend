<?php

namespace PuraUser\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use PuraUserModel\Entity\PuraUserEntity;
use PuraUserModel\Repository\PuraUserRepository;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Authentication\UserInterface;
use Zend\Expressive\Flash\FlashMessageMiddleware;
use Zend\Expressive\Session\SessionMiddleware;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Filter\StaticFilter;
use Zend\Filter\Boolean;
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

    private $puraUserRepository;

    private $puraUserList;

    /**
     * EditHandler constructor.
     * @param TemplateRendererInterface $template
     * @param Form $barcodeEntryForm
     */
    public function __construct(
        TemplateRendererInterface $template,
        PuraUserRepository        $puraUserRepository
    ) {
        $this->template           = $template;
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
        $message = '';
        $barcode = $request->getAttribute('barcode');

        /** @var PuraUserEntity $puraUserEntity */
        $puraUserEntity
            = $this->puraUserRepository->getSinglePuraUser($barcode);

        $flashMessages = $request->getAttribute(FlashMessageMiddleware  ::FLASH_ATTRIBUTE);
        $message = $flashMessages->getFlash('message');

        if ($request->getMethod() === 'POST') {
            $alephNr = $request->getParsedBody()['edit-alephNr'];
            $remark  = $request->getParsedBody()['edit-remark'];
            $isMemberEducationInstitution =
                $request->getParsedBody()['edit-member-education-institution'];

            // filter:
            $alephNr = StaticFilter::execute($alephNr, 'StringTrim');
            $alephNr = StaticFilter::execute($alephNr, 'StripTags');
            $remark = StaticFilter::execute($remark, 'StripTags');

            $booleanFilter = new Boolean();
            $isMemberEducationInstitution =
                $booleanFilter->filter($isMemberEducationInstitution);

            $request = $request->withAttribute(
                'alephNr',
                $alephNr
            );
            $request = $request->withAttribute(
                'remark',
                $remark
            );
            $request = $request->withAttribute(
                'member_education_institution',
                $isMemberEducationInstitution
            );

            // save alephNr+remark with savePuraUser(entity)
            $dbReturnCode = -1;
            $puraUserEntity->setLibrarySystemNumber($alephNr);
            $puraUserEntity->setRemarks($remark);
            $puraUserEntity
                ->setIsMemberEducationInstitution($isMemberEducationInstitution);
            $dbReturnCode = $this->puraUserRepository->savePuraUser($puraUserEntity);

            if ($dbReturnCode < 0) {
                $message = 'There was an error saving the aleph number to the database.';
            } else {
                $message = 'User saved.';
            }
        }

        $session = $request->getAttribute(
            SessionMiddleware::SESSION_ATTRIBUTE
        );
        $sessionData = $session->get(UserInterface::class);
        $libraryCode = $sessionData['roles'][0];
        $puraUserList = $this->puraUserRepository
            ->getFilteredListOfAllUsersFromALibrary('', $libraryCode);

        return new HtmlResponse(
            $this->template->render(
                'purauser::edit-page', [
                      'puraUserList' => $puraUserList,
                      'puraUserEntity' => $puraUserEntity,
                      'message' => $message
                ]
            )
        );
    }
}