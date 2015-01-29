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
     */
    public function convertMenuItemsToJson (Cti_Menubuilder_Model_Menu $menu)
    {
        return $this->__convertMenuItemsToTree($menu, 'json');
    }

    private function __convertMenuItemsToTree (Cti_Menubuilder_Model_Menu $menu, $return = 'object')
    {
        $items = $menu->getItems();
        $sortItems = array();
        $tree = array();

        $sortItems = $this->__createTree($items);

        switch ($return) {
            case 'json':
                $tree = Mage::helper('core')->jsonEncode($sortItems);
                break;
            default:
                break;
        }

        return $tree;
    }

    private function __createTree($items, $parentId = 0)
    {
        $itemTree = array();

        foreach ($items as $item) {
            if ($item['parent_id'] == $parentId) {
                $itemTree[$parentId][] = array(
                    'id'    => $item['item_id'],
                    'label' => $item['name'],
                    'data'  => $item,
                );
                $itemTree[$parentId][$item['item_id']]['children'] = $this->__createTree($items, $item['item_id']);
            }
        }

        return $itemTree;
    }
}