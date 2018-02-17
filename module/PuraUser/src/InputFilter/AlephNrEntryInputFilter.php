<?php
/**
 * AlephNrEntryInputFilter
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 09.02.18
 * Time: 15:21
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
 * @package  User_InputFilter
 * @author   Matthias Edel<matthias.edel@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.swissbib.org
 */
namespace PuraUser\InputFilter;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\InputFilter;

/**
 * AlephNrEntryInputFilter
 *
 * @category Swissbib_VuFind2
 * @package  User_InputFilter
 * @author   Matthias Edel<matthias.edel@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 */
class AlephNrEntryInputFilter extends InputFilter
{
    public function init()
    {
        $factory = $this->getFactory();

        $alephNr = $factory->createInput(['name' => 'alephNr']);
        $alephNr->setRequired(true);
        $alephNr->getFilterChain()->attachByName(StripTags::class);
        $alephNr->getFilterChain()->attachByName(StringTrim::class);

        $this->add($alephNr);
    }
}