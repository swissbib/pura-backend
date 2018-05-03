<?php

/**
 * ActivatePublisherHandler
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 13.02.18
 * Time: 14:38
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @category Swissbib_VuFind2
 * @package  Handler
 * @author   Lionel Walter <lionel.walter@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.swissbib.org
 */

namespace Publisher\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Publisher\BusinessLogicHelper\Publisher;
use PuraUserModel\Entity\PuraUserEntity;
use PuraUserModel\Repository\PuraUserRepositoryInterface;
use SwitchSharedAttributesAPIClient\PublishersList;
use SwitchSharedAttributesAPIClient\PuraSwitchClient;
use Zend\Diactoros\Response\JsonResponse;

/**
 * ActivatePublisherHandler
 *
 * @category Swissbib_VuFind2
 * @package  Handler
 * @author   Lionel Walter <lionel.walter@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 */
class ReloadUsersHandler implements RequestHandlerInterface
{
    /**
     * @var array $switchConfig
     */
    protected $switchConfig;

    /** @var PuraUserRepositoryInterface $puraUserRepository */
    private $puraUserRepository;

    /**
     * ActivatePublisherHandler constructor.
     */
    public function __construct($switchConfig, $puraUserRepository)
    {
        $this->switchConfig=$switchConfig;
        $this->puraUserRepository=$puraUserRepository;
    }

    /**
     * Handle the request and return a response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $publisherHelper = new Publisher($this->switchConfig, $this->puraUserRepository);

        $libraryCode = 'Z01';

        $puraUserList = $this->puraUserRepository
            ->getAllActiveUsersFromALibrary($libraryCode);

        /** @var PuraUserEntity $puraUser */
        foreach ($puraUserList as $puraUser) {
            $retVal = $publisherHelper->activatePublishers($puraUser->getEduId(), $puraUser->getBarcode(), $libraryCode);

            echo $retVal['message'];

            echo $puraUser->getFirstname();
            echo ' ';
            echo $puraUser->getLastname();
            echo ' ';
            echo $puraUser->getEduId();
            echo '<br>';
        }
    }
}