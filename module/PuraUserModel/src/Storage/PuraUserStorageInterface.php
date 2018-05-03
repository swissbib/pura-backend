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
    public function getBarcodeExists($barcode, $libraryCode);
    public function getFilteredListOfAllUsersFromALibrary($filter, $libraryCode);
    public function getAllActiveUsersFromALibrary($libraryCode);
    public function getSinglePuraUser($barcode);
    public function savePuraUserAlephNr($alephNr, $barcode);
    public function savePuraUser($puraUser);
    public function blockUser($barcode);
    public function unBlockUser($barcode);
}
