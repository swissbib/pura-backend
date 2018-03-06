<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 13.02.18
 * Time: 14:26
 */

namespace PuraUserModel\Storage;

interface PuraUserStorageInterface
{
    public function getBarcodeExists($barcode);
    public function getListOfAllUsers();
    public function getFilteredListOfAllUsers($filter);
    public function getSinglePuraUser($barcode);
    public function savePuraUserAlephNr($alephNr, $barcode);
    public function savePuraUser($puraUser);
}
