<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 13.02.18
 * Time: 14:26
 */

namespace PuraUser\Model\Storage;

interface PuraUserStorageInterface
{
    public function getListOfAllUsers();
    public function getFiteredListOfAllUsers($filter);
    public function getSingleUser($barcode);
}
