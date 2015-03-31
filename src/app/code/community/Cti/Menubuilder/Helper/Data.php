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
            if ($menuItem['parent_id'] == $parentId) {
                // Check if the item has children
                $childItems = $this->_createTree($items, $menuItem['item_id']);
                // Assign the children to the item
                $menuItem['children'] = $childItems;
                $itemTree[] = array(
                    'id'    => $menuItem['item_id'],
                    'label' => $menuItem['name'],
                    'children'  => $menuItem['children'],
                );
                // Remove the item from the overall list so it isn't processed again
                unset($items[$arrayKey]);
            }
        }

        return $itemTree;
    }
}