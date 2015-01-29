<?php
class Cti_Menubuilder_Block_Adminhtml_Edit_Menu extends
    Mage_Adminhtml_Block_Widget_Form
{
    public function __construct ()
    {
        parent::__construct();
    }

    public function getItemJsonUrl ()
    {
        $menu = Mage::registry('cti_menubuilder_menu');

        if ($menu->getMenuId()) {
            return $this->getUrl(
                '*/*/getmenuitems',
                array(
                    'menu_id' => $menu->getMenuId(),
                )
            );
        }
        return false;
    }
}