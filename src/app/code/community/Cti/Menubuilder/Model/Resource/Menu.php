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
            $newStores = array();
            // If stores have been specified
            if ($object->getStores()) {
                if (is_array($object->getStores())) {
                    $newStores = $object->getStores();
                } else {
                    $newStores[] = $object->getStores();
                }
            }

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
            $oldItems = $this->_lookupMenuItemIds($object->getMenuId());
            $newItems = $object->getItems();
            $new = array();

//            $delete = array_diff($oldItems, $new);
            $table = $this->getTable('cti_menubuilder/menu_item');

//            if ($delete) {
//                $where = array(
//                    'item_id IN (?)'    => $delete
//                );
//
//                $this->_getWriteAdapter()->delete($table, $where);
//            }

            foreach ($newItems as $item) {
                $this->_addItemValues($object, $item);
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
            $itemValues = $this->_lookupItemValues($items);
            $object->setData('items', $itemValues);
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

    private function _lookupMenuItemIds ($id)
    {
        $adapter = $this->_getReadAdapter();

        $select = $adapter->select()
            ->from($this->getTable('cti_menubuilder/menu_item'), 'item_id')
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
            ->from(
                array(
                    'menu_item'  => $this->getTable('cti_menubuilder/menu_item')
                )
            )
            ->where('menu_id = :menu_id');

        $binds = array(
            ':menu_id'  => (int) $id
        );

        return $adapter->fetchAll($select, $binds);
    }

    /**
     * Look up an item's value and assign them to the array
     *
     * @param array $items the items to get values with
     *
     * @return array
     */
    private function _lookupItemValues ($items)
    {
        $adapter = $this->_getReadAdapter();

        $itemValues = array();

        // Get the item IDs and use them as the index
        foreach ($items as $item) {
            $itemValues[$item['item_id']] = $item;
        }

        $select = $adapter->select()
            ->from(
                array(
                    'item_value'    =>
                        $this->getTable('cti_menubuilder/item_value')
                ),
                array('item_id','field', 'value')
            )->where(
                'item_id IN (?)',
                array_keys($itemValues)
            );

        $results = $adapter->fetchAll($select);

        // Loop through the results and associate the value to the item
        foreach ($results as $value) {
            $itemValues[$value['item_id']][$value['field']] = $value['value'];
        }

        return $itemValues;
    }

    /**
     * Adds an item and the values
     *
     * @param Cti_Menubuilder_Model_Menu $object the menu the item is associated with
     * @param array                      $item   the item
     *
     * @return bool
     */
    private function _addItemValues (Cti_Menubuilder_Model_Menu $object, $item)
    {
        $itemValues = array();

        $itemData = array(
            'item_id'   => (isset($item['item_id']) ? $item['item_id'] : null),
            'menu_id'   => $object->getMenuId(),
            'parent_id' => (isset($item['parent_id']) ? $item['parent_id'] : 0)
        );

        $this->_getWriteAdapter()->insertOnDuplicate(
            $this->getTable('cti_menubuilder/menu_item'),
            $itemData,
            array('parent_id')
        );

        if (isset($item['item_id'])) {
            $id = $item['item_id'];
        } else {
            $id = $this->_getWriteAdapter()->lastInsertId($this->getTable('cti_menubuilder/menu_item'));
        }

        foreach ($item as $key => $value) {
            if (!in_array($key, array_keys($itemData))) {
                $itemValues[] = array(
                    'item_id'   => $id,
                    'field'     => $key,
                    'value'     => $value
                );
            }
        }

        $this->_getWriteAdapter()->delete(
            array(
                $this->getTable('cti_menubuilder/item_value')
            ),
            array(
                'item_id = ?' => $id
            )
        );

        $this->_getWriteAdapter()->insertMultiple(
            $this->getTable('cti_menubuilder/item_value'),
            $itemValues
        );

        return true;
    }
}