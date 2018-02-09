<?php
declare(strict_types=1);

namespace User\Form;

use Zend\Form\Element\Password;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class LoginForm extends Form
{
    public function init()
    {
        $this->setName('login-form');
        $this->add([
            'type' => Text::class,
            'name' => 'username',
            'options' => [
                'label' => 'Username',
            ],
        ]);

        $this->add([
            'type' => Password::class,
            'name' => 'password',
            'options' => [
                'label' => 'Password',
            ],
        ]);

        $this->add([
            'name' => 'Login',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Login',
            ],
        ]);
    }
}