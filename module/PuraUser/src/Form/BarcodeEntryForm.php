<?php
declare(strict_types=1);

namespace PuraUser\Form;

use Zend\Form\Form;

class BarcodeEntryForm extends Form
{
    public function init()
    {
        $this->setName('barcodeentry-form');
        parent::init();
    }
}