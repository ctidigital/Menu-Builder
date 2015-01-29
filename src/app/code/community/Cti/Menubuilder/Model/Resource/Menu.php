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
     * Operations after the object has been saved
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterSave (Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            // The stores the menu is currently assigned on
            $oldStores = $this->_lookupStoreIds($object->getMenuId());
            // The new stores the menu is assigned on
            $newStores = $object->getStores();

            $table = $this->getTable('cti_menubuilder/menu_store');
            // Get the new stores the menu can be assigned to
            $insert = array_diff($newStores, $oldStores);
            // Get the existing stores that should be deleted
            $delete = array_diff($oldStores, $newStores);

            if ($delete) {
                $where = array(
                    'menu_id = ?'   => (int) $object->getMenuId(),
                    'store_id IN (?)'   => $delete,
                );

                $this->_getWriteAdapter()->delete($table, $where);
            }

            if ($insert) {
                $data = array();

                foreach ($insert as $storeId) {
                    $data[] = array(
                        'menu_id'   => (int) $object->getMenuId(),
                        'store_id'  => (int) $storeId,
                    );
                }

                $this->_getWriteAdapter()->insertMultiple($table, $data);
            }
        }
        return parent::_afterSave($object);
    }

    /**
     * Operations after the the object has been loaded
     *
     * @param Mage_Core_Model_Abstract $object the menu that is being loaded
     *
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterLoad (Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            // Get the store IDs the menu is assigned to
            $storeIds = $this->_lookupStoreIds($object->getId());
            $object->setData('stores', $storeIds);

            // Get the item IDs that are associated to the menu
            $items = $this->_lookupItems($object->getMenuId());
            $object->setData('items', $items);
        }
        return parent::_afterLoad($object);
    }

    /**
     * Look up the store IDs a menu is associated to using the menu ID
     *
     * @param int $id the ID of the menu
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

    /**
     * Look up the items associated to a menu
     *
     * @param int $id the ID of the menu
     *
     * @return array
     */
    private function _lookupItems ($id)
    {
        $adapter = $this->_getReadAdapter();

        $select = $adapter->select()
            ->from($this->getTable('cti_menubuilder/menu_item'), '*')
            ->where('menu_id = :menu_id');

        $binds = array(
            ':menu_id'  => (int) $id
        );

        return $adapter->fetchAll($select, $binds);
    }
}