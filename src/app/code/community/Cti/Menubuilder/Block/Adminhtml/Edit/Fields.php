<?php
class Cti_Menubuilder_Block_Adminhtml_Edit_Fields extends
    Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm ()
    {
        $form = new Varien_Data_Form(
            array (
                'id'    => 'fields_edit_form',
                'action'    => '',
                'method'    => 'post',
                'enctype'   => 'multipart/form-data'
            )
        );

        $form->setUseContainer(true);

        $fieldSet = $form->addFieldset(
            'cti_menubuilder_form_fields',
            array(
                'legend'    => $this->__('Fields')
            )
        );

        $fieldSet->addField(
            'label',
            'text',
            array(
                'label' => $this->__('Label'),
                'name'  => 'label'
            )
        );

        $this->_addItemTypeField($fieldSet);

        $this->_addLinkFields($fieldSet);

        // Get the menu fields from the XML
        $fields = Mage::helper('cti_menubuilder')->getMenuFields();

        // Loop through the fields and add to the form
        foreach ($fields as $name => $field) {
            $data = array();

            if (!isset($field['type']) || !isset($field['label'])) {
                continue;
            }

            $data['name'] = $name;

            $data['label']  = $this->__($field['label']);

            if (isset($field['class'])) {
                $data['class'] = $field['class'];
            } else {
                $data['class'] = '';
            }

            if (isset($field['required']) && $field['required'] == true) {
                $data['required'] = true;
                $data['class'] = $data['class'] . ' required-entry';
            } else {
                $data['required'] = false;
            }

            $fieldSet->addField(
                $name,
                $field['type'],
                $data
            );
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }

    private function _addItemTypeField ($fieldSet)
    {
        $fieldSet->addField(
            'item_type',
            'select',
            array(
                'label'     => $this->__('Item Type'),
                'options'   => array(
                    'link'          => $this->__('Link'),
                    'structural'    => $this->__('Structural'),
                ),
                'name'      => 'item_type'
            )
        );

        return $fieldSet;
    }

    private function _addLinkFields ($fieldSet)
    {
        $fieldSet->addField(
            'link_type',
            'select',
            array(
                'label'     => $this->__('Link Type'),
                'options'   => array(
                    'standard'  => $this->__('Standard'),
                    'cms'       => $this->__('CMS'),
                    'category'  => $this->__('Category'),
                    'product'   => $this->__('Product')
                ),
                'name'      => 'link_type'
            )
        );

        $fieldSet->addField(
            'link',
            'text',
            array(
                'label' => $this->__('Link'),
                'name'  => 'link'
            )
        );

        return $fieldSet;
    }
}