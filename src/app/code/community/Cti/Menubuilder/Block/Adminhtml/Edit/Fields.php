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
}