<?php
/**
 * LoginFactory
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 09.02.18
 * Time: 14:15
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
 * @package  User_Handler
 * @author   Lionel Walter <lionel.walter@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.swissbib.org
 */
namespace PuraUser\Handler;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use PuraUser\InputFilter\BarcodeEntryInputFilter;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Form\FormElementManager;

/**
 * BarcodeEntryFactory
 *
 * @category Swissbib_VuFind2
 * @package  User_Handler
 * @author   Matthias Edel <matthias.edel@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 */
class BarcodeEntryFactory implements FactoryInterface
{

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    )
    {
        $template  = $container->get(TemplateRendererInterface::class);
        $inputFilterManager = $container->get(InputFilterPluginManager::class);
        $loginInputFilter = $inputFilterManager->get(BarcodeEntryInputFilter::class);
        $barcodeEntryForm = new Form();
        $barcodeEntryForm->setInputFilter($loginInputFilter);

        return new BarcodeEntryHandler($template, $barcodeEntryForm);
    }
}