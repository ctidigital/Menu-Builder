<?php
/**
 * Class Cti_Menubuilder_Block_Adminhtml_Manage_Grid
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
 * Grid for managing menus
 *
 * @category  Cti
 * @package   Cti_Menubuilder
 * @author    Paul Partington <p.partington@ctidigital.com>
 * @copyright 2015 CTI Digital (http://www.ctidigital.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link      http://www.ctidigital.com
 */
class Cti_Menubuilder_Block_Adminhtml_Manage_Grid extends
    Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set the grid parameters
     */
    public function __construct ()
    {
        parent::__construct();
        $this->setId('menubuilder_grid');
        $this->setDefaultSort('menu_id');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Get the menu collection and set it for the grid
     *
     * @var $collection Cti_Menubuilder_Model_Menu
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection ()
    {
        $collection = Mage::getModel('cti_menubuilder/menu')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Set up the grid columns
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns ()
    {
        $this->addColumn(
            'menu_id', array(
                'header'    => Mage::helper('cti_menubuilder')->__('Menu ID'),
                'index'     => 'menu_id',
            )
        );

        $this->addColumn(
            'name', array(
                'header'    => Mage::helper('cti_menubuilder')->__('Menu Name'),
                'index'     => 'name',
            )
        );

        $this->addColumn(
            'identifier', array(
                'header'    =>
                    Mage::helper('cti_menubuilder')->__('Menu Identifier'),
                'index'     => 'identifier',
            )
        );

        return parent::_prepareColumns();
    }
}