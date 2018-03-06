<?php

/**
 * PublisherHandler
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
 * @package  BusinessLogicHelper
 * @author   Matthias Edel <matthias.edel@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.swissbib.org
 */

namespace Publisher\BusinessLogicHelper;

use PuraUserModel\Entity\PuraUserEntity;
use PuraUserModel\Repository\PuraUserRepository;
use SwitchSharedAttributesAPIClient\PublishersList;
use SwitchSharedAttributesAPIClient\PuraSwitchClient;

/**
 * ActivatePublisher
 *
 * @category Swissbib_VuFind2
 * @package  BusinessLogicHelper
 * @author   Matthias Edel <matthias.edel@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 */
class Publisher
{
    /**
     * @var array $switchConfig
     */
    protected $switchConfig;

    /** @var PuraUserRepository $puraUserRepository */
    protected $puraUserRepository;

    /**
     * Publisher constructor.
     *
     * @param array              $switchConfig       switchConfig
     * @param PuraUserRepository $puraUserRepository puraUserRepository
     */
    public function __construct(
        $switchConfig,
        PuraUserRepository $puraUserRepository
    ) {
        $this->switchConfig       = $switchConfig;
        $this->puraUserRepository = $puraUserRepository;
    }

    /**
     * activate publisher
     */
    public function activatePublisher($barcode, $libraryCode)
    {
            $filePath = __DIR__ . '/../../../../public/publishers-libraries.json';
            $publishersJsonData
                = file_exists($filePath) ? file_get_contents($filePath) : '';

            /**
             * @var PublishersList $publishersList
             */
            $publishersList = new PublishersList();
            $publishersList->loadPublishersFromJsonFile($publishersJsonData);
            $puraSwitchClient = new PuraSwitchClient($this->switchConfig, $publishersList);

            /** @var PuraUserEntity $puraUserEntity */
            $puraUserEntity = new PuraUserEntity();
            $puraUserEntity->setBarcode($barcode);
            $puraUserEntity->setAccessCreated(date("Y-m-d H:i:s"));
            $puraUserEntity->setHasAccess(true);

            $this->puraUserRepository->savePuraUser($puraUserEntity);
            $result = $puraSwitchClient->activatePublishers($barcode, $libraryCode);

            return json_encode(['success : ' . $result['success'] => $result['message']]);
    }
}