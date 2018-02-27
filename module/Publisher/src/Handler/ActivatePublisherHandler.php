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
use SwitchSharedAttributesAPIClient\PublishersList;
use SwitchSharedAttributesAPIClient\PuraSwitchClient;
use SwitchSharedAttributesAPIClient\SwitchSharedAttributesAPIClient;
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
class ActivatePublisherHandler implements RequestHandlerInterface
{

    /**
     * Handle the request and return a response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $credentials['auth_user'] = "natlic";
        $credentials['auth_password'] = "Amg6vZXo";
        $switchApiConfg['national_licence_programme_group_id'] = '1d3baa7b-da70-440d-b777-5bb2d11f8718';
        $switchApiConfg['base_endpoint_url'] = 'https://test.eduid.ch/sg/index.php';
        $switchApiConfg['schema_patch'] = 'urn:ietf:params:scim:api:messages:2.0:PatchOp';
        $switchApiConfg['operation_add'] = 'add';
        $switchApiConfg['operation_remove'] = 'remove';
        $switchApiConfg['path_member'] = 'members';


        $filePath = __DIR__ . '/../../../../public/publishers-libraries.json';


        $publishersJsonData
            = file_exists($filePath) ? file_get_contents($filePath) : '';

        /**
         * @var PublishersList $publishersList
         */
        $publishersList = new PublishersList();

        $publishersList->loadPublishersFromJsonFile($publishersJsonData);

        $puraSwitchClient = new PuraSwitchClient($credentials, $switchApiConfg, $publishersList);

        $result = $puraSwitchClient->activatePublishers('169330697816@test.eduid.ch', 'Z01');

        //Here Needs to Store in the DB the date of activation and set "has_access" to true

        return new JsonResponse(['success : ' . $result['success'] => $result['message']]);
    }
}