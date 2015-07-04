<?php
/**
 * Class Cti_Menubuilder_Helper_Data
 *
 * PHP Version 5
 *
 * @category  Cti
 * @package   Cti_Menubuilder
 * @author    Paul Partington <p.partington@ctidigital.com>
 * @copyright 2015 CTI Digital (http://www.ctidigital.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link      http://www.ctidigital.com
 */
/**
 * General helper for Menu Builder
 *
 * @category  Cti
 * @package   Cti_Menubuilder
 * @author    Paul Partington <p.partington@ctidigital.com>
 * @copyright 2015 CTI Digital (http://www.ctidigital.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link      http://www.ctidigital.com
 */
class Cti_Menubuilder_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Convert a menu's items to JSON
     *
     * @param Cti_Menubuilder_Model_Menu $menu the menu to convert
     *
     * @return string JSON
     */
    public function convertMenuItemsToJson (Cti_Menubuilder_Model_Menu $menu)
    {
        return $this->_convertMenuItemsToTree($menu, 'json');
    }

    /**
     * Convert a menu's item to an object
     *
     * @param Cti_Menubuilder_Model_Menu $menu
     *
     * @return array|string
     */
    public function convertMenuItemsToTree (Cti_Menubuilder_Model_Menu $menu)
    {
        return $this->_convertMenuItemsToTree($menu);
    }

    public function convertMenuItemsToArray ($json)
    {
        $items = Mage::helper('core')->jsonDecode($json);

        $tree = $this->_flattenItemTree($items);

        return $tree;
    }

    private function _flattenItemTree ($items)
    {
        $itemTree = array();

        if (empty($items)) {
            return $itemTree;
        }

        foreach ($items as $item) {
            if (isset($item['children'])) {
                $children = $this->_flattenItemTree($item['children']);
                unset($item['children']);
                $itemTree[] = $item;
                foreach ($children as $child) {
                    $itemTree[] = $child;
                }
            } else {
                $itemTree[] = $item;
            }
        }

        return $itemTree;
    }

    /**
     * Convert a menu's items into a tree format
     *
     * @param Cti_Menubuilder_Model_Menu $menu   menu with items to process
     * @param string                     $return the format to return the item tree
     *
     * @return array|string
     */
    private function _convertMenuItemsToTree (
        Cti_Menubuilder_Model_Menu $menu,
        $return = 'object'
    ) {
        $items = $menu->getItems();
        $sortItems = array();
        $tree = array();

        $sortItems = $this->_createTree($items);

        switch ($return) {
            case 'json':
                $tree = Mage::helper('core')->jsonEncode($sortItems);
                break;
            default:
                $tree = $sortItems;
                break;
        }

        return $tree;
    }

    /**
     * Create a tree of items
     *
     * Code referenced from http://stackoverflow.com/a/8841921/442326
     *
     * @param array $items    the items to loop through
     * @param int   $parentId the current parent_id to start searching at
     *
     * @return array
     */
    private function _createTree($items, $parentId = 0)
    {
        $itemTree = array();
        // Loop through each item
        foreach ($items as $arrayKey => $menuItem) {
            // If the item's parent_id matches the parameter's, start to process it
            if ((isset($menuItem['parent_id'])
                && $menuItem['parent_id'] == $parentId)
            ) {
                if (isset($menuItem['item_id'])) {
                    // Check if the item has children
                    $childItems = $this->_createTree($items, $menuItem['item_id']);
                    // Assign the children to the item
                    $menuItem['children'] = $childItems;
                    // Set the values used by JQTree
                    $nodeItem = array(
                        'id'        => $menuItem['item_id'],
                        'item_id'   => $menuItem['item_id'],
                        'parent_id' => $menuItem['parent_id'],
                        'label'     => $menuItem['name'],
                        'children'  => $menuItem['children']
                    );
                    // Get the fields for the menu and add them to the item
                    $fields = $this->getMenuFields();
                    foreach ($fields as $fieldName => $fieldValues) {
                        if (isset($menuItem[$fieldName])) {
                            $nodeItem[$fieldName] = $menuItem[$fieldName];
                        } else {
                            $nodeItem[$fieldName] = '';
                        }
                    }
                    // Assign any other values to the item tree
                    foreach ($menuItem as $itemKey => $value) {
                        if (!in_array($itemKey, array_keys($nodeItem))) {
                            $nodeItem[$itemKey] = $value;
                        }
                    }
                    $itemTree[] = $nodeItem;
                }
            }
            // Remove the item from the overall list so it isn't processed again
            unset($items[$arrayKey]);
        }

        return $itemTree;
    }

    /**
     * Gets the fields used when adding a new menu item
     *
     * @return array|string
     */
    public function getMenuFields ()
    {
        $fields = Mage::app()->getConfig()->getNode('cti_menubuilder/fields');

        return $fields->asArray();
    }

    public function getLinkUrl ($item)
    {
        $url = '';
        if (isset($item['link_type'])) {
            switch ($item['link_type']) {
                case 'standard':
                    $url = $this->_generateStandardUrl($item['link']);
                    break;
            }
        }
        return $url;
    }

    private function _generateStandardUrl ($url)
    {
        $url = preg_replace('/^\//', '', $url);
        return Mage::getUrl($url);
    }
}