<?php
/**
 * Class Cti_Menubuilder_Block_Menu
 *
 * @category  Cti
 * @package   Cti_Menubuilder
 * @author    Paul Partington <p.partington@ctidigital.com>
 * @copyright 2015 CTI Digital (http://www.ctidigital.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link      http://www.ctidigital.com
 */
/**
 * Renders the menus on the frontend
 *
 * @cateogry  Cti
 * @package   Cti_Menubuilder
 * @author    Paul Partington <p.partington@ctidigital.com>
 * @copyright 2015 CTI Digital (http://www.ctidigital.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link      http://www.ctidigital.com
 */
class Cti_Menubuilder_Block_Menu extends Mage_Core_Block_Template
{
    private $_identifier;

    protected function _construct ()
    {
        if ($this->getData('identifier')) {
            $this->setIdentifier($this->getData('identifier'));
        }
    }

    public function getIdentifier ()
    {
        return $this->_identifier;
    }

    public function setIdentifier ($identifier)
    {
        $this->_identifier = $identifier;
        return $this;
    }

    public function getMenu ()
    {
        return $this->_renderMenu();
    }

    private function _renderMenu ()
    {
        $menu = Mage::getModel('cti_menubuilder/menu')
            ->load($this->getIdentifier(), 'identifier');

        $items = Mage::helper('cti_menubuilder')->convertMenuItemsToTree($menu);

        $html = $this->_renderItems ($items);

        return $html;
    }

    private function _renderItems ($items, $level = 0)
    {
        $html = '';

        if (is_array($items)) {
            foreach ($items as $item) {
                $children = false;
                if (count($item['children']) > 0) {
                    $children = true;
                }

                $class = 'level' . $level;

                if ($children) {
                    $class .= ' parent';
                }

                $html .= '<li class="' .  $class . '">';
                $temporary = '<a href="' . Mage::helper('cti_menubuilder')->getLinkUrl($item) . '">' . $item['title'] . '</a>';
                $html .= $temporary;

                if ($children) {
                    $html .= '<ol>';
                    $html .= $this->_renderItems($item['children'], $level + 1);
                    $html .= '</ol>';
                }

                $html .= '</li>';
            }
        }

        return $html;
    }
}