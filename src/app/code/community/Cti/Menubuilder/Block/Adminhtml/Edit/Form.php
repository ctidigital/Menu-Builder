<?php
/**
 * Class Cti_Menubuilder_Block_Adminhtml_Edit_Form
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
 * Form for managing menu details
 */
class Cti_Menubuilder_Block_Adminhtml_Edit_Form extends
    Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Setup the form block
     */
    public function __construct ()
    {
        parent::__construct();
        $this->setTitle(Mage::helper('cti_menubuilder')->__('Menu'));
    }

    /**
     * Prepare form fields
     *
     * @var $menu Cti_Menubuilder_Model_Menu
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm ()
    {
        $menu = Mage::registry('cti_menubuilder_menu');

        $form = new Varien_Data_Form(
            array(
                'id'        => 'edit_form',
                'action'    => $this->getUrl(
                    '*/*/save',
                    array(
                        'menu_id' => $menu->getMenuId()
                    )
                ),
                'method'    => 'post',
                'enctype'   => 'multipart/form-data',
            )
        );

        $form->setUseContainer(true);

        $fieldset = $form->addFieldset(
            'cti_menubuilder_menu_form',
            array(
                'legend'    =>
                    Mage::helper('cti_menubuilder')->__('Menu Information'),
            )
        );

        if ($menu->getMenuId()) {
            $fieldset->addField(
                'menu_id',
                'hidden',
                array(
                    'name'    => 'menu_id',
                )
            );
        }

        $fieldset->addField(
            'name',
            'text',
            array(
                'label'     => Mage::helper('cti_menubuilder')->__('Name'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'name',
            )
        );

        $fieldset->addField(
            'identifier',
            'text',
            array(
                'label'     => Mage::helper('cti_menubuilder')->__('Identifier'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'identifier',
            )
        );

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'stores',
                'multiselect',
                array(
                    'label' => Mage::helper('cti_menubuilder')->__('Store View'),
                    'class' => 'required-entry',
                    'required'  => true,
                    'name'      => 'stores[]',
                    'values'    => Mage::getSingleton('adminhtml/system_store')
                        ->getStoreValuesForForm(false, true),
                )
            );
        } else {
            $fieldset->addField(
                'stores',
                'hidden',
                array(
                    'name'  => 'stores',
                    'value' => Mage::app()->getStore(true)->getId(),
                )
            );
            $menu->setStores(Mage::app()->getStore(true)->getId());
        }

        $form->setValues($menu->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }
}