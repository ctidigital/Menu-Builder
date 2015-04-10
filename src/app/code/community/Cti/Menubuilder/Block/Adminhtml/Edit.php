<?php
/**
 * Class Cti_Menubuilder_Block_Adminhtml_Edit
 *
 * PHP version 5
 *
 * @category  Cti
 * @package   Cti_Menubuilder
 * @author    Paul Partington <p.partington@ctidigital.com>
 * @copyright 2015 CTI Digital
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link      http://www.ctidigital.com
 */
/**
 * Container for the menu form
 *
 * @category  Cti
 * @package   Cti_Menubuilder
 * @author    Paul Partington <p.partington@ctidigital.com>
 * @copyright 2015 CTI Digital
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link      http://www.ctidigital.com
 */
class Cti_Menubuilder_Block_Adminhtml_Edit extends
    Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Set values for the form container
     */
    public function __construct ()
    {
        $this->_objectId = 'menu_id';
        $this->_blockGroup = 'cti_menubuilder';
        $this->_controller = 'adminhtml';

        parent::__construct();

        $this->_updateButton('save', 'onclick', 'saveMenu()');
    }

    /**
     * Get the header text for the form
     *
     * @return string
     */
    public function getHeaderText ()
    {
        if (Mage::registry('cti_menubuilder_menu')->getMenuId()) {
            return Mage::helper('cti_menubuilder')->__(
                'Edit menu %s',
                $this->escapeHtml(
                    Mage::registry('cti_menubuilder_menu')->getName()
                )
            );
        } else {
            return Mage::helper('cti_menubuilder')->__('New Menu');
        }
    }

    /**
     * Add the forms
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout ()
    {
        if ($this->_blockGroup && $this->_controller && $this->_mode) {
            $this->setChild(
                'form',
                $this->getLayout()->createBlock(
                    $this->_blockGroup . '/' .
                    $this->_controller . '_' .
                    $this->_mode . '_form'
                )
            );
        }

        return parent::_prepareLayout();
    }

    public function getMenuCreator ()
    {
        if (Mage::registry('cti_menubuilder_menu')->getMenuId()) {
            return $this->getChildHtml('cti_menubuilder.edit.menu');
        }
        return false;
    }
}