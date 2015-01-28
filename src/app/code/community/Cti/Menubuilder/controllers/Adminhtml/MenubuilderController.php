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
        $this->loadLayout();
        $this->renderLayout();
    }
}