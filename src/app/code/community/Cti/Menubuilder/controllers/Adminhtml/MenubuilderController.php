<?php
/**
 * Class Cti_Menubuilder_Adminhtml_MenubuilderController
 *
 * PHP version 5
 *
 * @category  Cti
 * @package   Cti_Menubuilder
 * @author    Paul Partington <p.partington@ctidigital.com>
 * @copyright 2015 CTI Digital (http://www.ctidigital.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link      http://www.ctidigital.com
 *
 */
/**
 * Controller for managing menus created in Menu Builder
 *
 * @category  Cti
 * @package   Cti_Menubuilder
 * @author    Paul Partington <p.partington@ctidigital.com>
 * @copyright 2015 CTI Digital (http://www.ctidigital.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link      http://www.ctidigital.com
 *
 */
class Cti_Menubuilder_Adminhtml_MenubuilderController extends
    Mage_Adminhtml_Controller_Action
{
    /**
     * Forward request to the manage action
     *
     * @return null
     */
    public function indexAction ()
    {
        $this->_forward('manage');
    }

    /**
     * Load the Menu Builder management grid
     *
     * @return null
     */
    public function manageAction ()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Forward the request to the edit action
     *
     * @return null
     */
    public function newAction ()
    {
        $this->_forward('edit');
    }

    /**
     * Edit menus
     *
     * @return null
     */
    public function editAction ()
    {
        $this->loadLayout()
            ->_setActiveMenu('cms/cti_menubuilder')
            ->_addBreadcrumb($this->__('CMS'), $this->__('CMS'))
            ->_addBreadcrumb($this->__('Edit Menu'), $this->__('Edit Menu'));

        // Load an empty menu
        $menu = Mage::getModel('cti_menubuilder/menu');

        // Get the menu_id parameter if one was sent
        $menuId = $this->getRequest()->getParam('menu_id');

        // If a menu_id was provided, load the menu
        if (is_numeric($menuId)) {
            $menu->load($menuId);

            // If no menu was found, return an error as it does not exist
            if ($menu->getId() == false) {
                Mage::getSingleton('adminhtml/session')
                    ->addError(
                        Mage::helper('cti_menubuilder')
                            ->__('The menu no longer exists.')
                    );
                $this->_redirect('*/*/');
                return false;
            }
        }

        // Add the menu to the registry so that it can be used in the form
        if (!Mage::registry('cti_menubuilder_menu')) {
            Mage::register('cti_menubuilder_menu', $menu);
        }

        $this->renderLayout();
    }

    /**
     * Save menus
     */
    public function saveAction ()
    {
        if ($data = $this->getRequest()->getPost()) {
            $menu = Mage::getModel('cti_menubuilder/menu');

            // If a menu_id was passed in, try to load the menu
            if ($menuId = $this->getRequest()->getParam('menu_id')) {
                $menu->load($menuId);
            } else {
                unset($data['menu_id']);
            }

            // Set the menu data with the values from the form
            $menu->setData($data);

            try {
                $menu->save();

                $this->_getSession()->addSuccess(
                    Mage::helper('cti_menubuilder')->__('The menu has been saved.')
                );

                $this->_getSession()->setFormData(false);

                // If save and continue was used go back to the edit form
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect(
                        '*/*/edit',
                        array(
                            'menu_id' => $menu->getMenuId(),
                        )
                    );
                }
                $this->_redirect('*/*');
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError(
                    Mage::helper('cti_menubuilder')
                        ->__('The menu could not be saved')
                );
            }
        }
        $this->_redirect('*/*');
    }

    public function getMenuItemsAction ()
    {
        if ($menuId = $this->getRequest()->getParam('menu_id')) {
            $menu = Mage::getModel('cti_menubuilder/menu')->load($menuId);
        }

        $json = Mage::helper('cti_menubuilder')->convertMenuItemsToJson($menu);
//        $json = array(
//            array(
//                'label' => 'Home Page',
//                'id'    => '1',
//            ),
//            array(
//                'label' => 'Categories',
//                'id'    => '2',
//                'children'  => array(
//                    array(
//                        'label' => 'Computers',
//                        'id'    => '3',
//                    ),
//                    array(
//                        'label' => 'MP3s',
//                        'id'    => '4',
//                    )
//                )
//            )
//        );

        $this->getResponse()->setBody($json);
    }
}