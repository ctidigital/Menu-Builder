<?php
/**
 * Class Cti_Menubuilder_Model_Resource_Menu
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
 * Class Cti_Menubuilder_Model_Resource_Menu
 *
 * @category  Cti
 * @package   Cti_Menubuilder
 * @author    Paul Partington <p.partington@ctidigital.com>
 * @copyright 2015 CTI Digital (http://www.ctidigital.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link      http://www.ctidigital.com
 */
class Cti_Menubuilder_Model_Resource_Menu extends
    Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Method for instantiating the menu resource
     *
     * @return Cti_Menubuilder_Model_Resource_Menu
     */
    public function _construct ()
    {
        $this->_init('cti_menubuilder/menu', 'menu_id');
    }

    /**
     * Operations after the the object has been loaded
     *
     * @param Mage_Core_Model_Abstract $object The menu that is being loaded
     *
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterLoad (Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            // Get the store IDs the menu is assigned to
            $storeIds = $this->_lookupStoreIds($object->getId());
            $object->setData('stores', $storeIds);
        }
        return parent::_afterLoad($object);
    }

    /**
     * Look up the store IDs a menu is associated to using the menu ID
     *
     * @param int $id The ID of the menu
     *
     * @return array
     */
    private function _lookupStoreIds ($id)
    {
        $adapter = $this->_getReadAdapter();

        $select = $adapter->select()
            ->from($this->getTable('cti_menubuilder/menu_store'), 'store_id')
            ->where('menu_id = :menu_id');

        $binds = array(
            ':menu_id'  => (int) $id
        );

        return $adapter->fetchCol($select, $binds);
    }
}