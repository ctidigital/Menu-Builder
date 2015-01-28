<?php
/**
 * Class Cti_Menubuilder_Model_Resource_Menu_Collection
 *
 * PHP version 5
 *
 * @category  Cti
 * @package   Cti_Menubuilder
 * @author    Paul Partington <p.partington@ctidigital.com>
 * @copyright 2015 CTI Digital (http://www.ctidigital.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link      http://www.ctidigital.com
 */
/**
 * Class Cti_Menubuilder_Model_Resource_Menu_Collection
 *
 * @category  Cti
 * @package   Cti_Menubuilder
 * @author    Paul Partington <p.partington@ctidigital.com>
 * @copyright 2015 CTI Digital (http://www.ctidigital.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link      http://www.ctidigital.com
 */
class Cti_Menubuilder_Model_Resource_Menu_Collection extends
    Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Method to instantiate the menu collection
     *
     * @return Cti_Menubuilder_Model_Resource_Menu_Collection
     */
    protected function _construct ()
    {
        $this->_init('cti_menubuilder/menu');
    }
}