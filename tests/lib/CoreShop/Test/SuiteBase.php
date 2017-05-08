<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2017 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
*/

namespace CoreShop\Test;

use Pimcore\Model\Object\AbstractObject;

/**
 * Class SuiteBase.
 */
class SuiteBase extends \PHPUnit_Framework_TestSuite
{
    /**
     * Setup.
     */
    protected function setUp()
    {
        AbstractObject::setHideUnpublished(false);
    }

    /**
     * Tear Down.
     */
    protected function tearDown()
    {
    }
}
