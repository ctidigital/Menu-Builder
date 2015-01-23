<?php
/**
 * Class Cti_Menubuilder_Block_Adminhtml_Manage
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
 * Grid for managing Menu Builder Menus
 *
 * @category  Cti
 * @package   Cti_Menubuilder
 * @author    Paul Partington <p.partington@ctidigital.com>
 * @copyright 2015 CTI Digital (http://www.ctidigital.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link      http://www.ctidigital.com
 */
class Cti_Menubuilder_Block_Adminhtml_Manage extends
    Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Set parameters to be used during block creation
     */
    public function __construct ()
    {
        parent::__construct();
        $this->_controller = 'adminhtml_manage';
        $this->_blockGroup = 'cti_menubuilder';
        $this->_headerText = $this->__('Menu Builder');
    }

    /**
     * Set the grid block for managing menus
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout ()
    {
        $grid = $this->getLayout()->createBlock(
            $this->_blockGroup.'/'.$this->_controller.'_grid',
            $this->_controller.'.grid'
        );
        $grid->setSaveParametersInSession(true);
        $this->setChild('grid', $grid);

        return parent::_prepareLayout();
    }
}