<?php
/**
 * Class Cti_Menubuilder_Model_Resource_Menu_Collection
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
 * Class Cti_Menubuilder_Model_Resource_Menu_Collection
 *
 * @category  Cti
 * @package   Cti_Menubuilder
 * @author    Paul Partington <p.partington@ctidigital.com>
 * @copyright 2015 CTI Digital (http://www.ctidigital.com)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link      http://www.ctidigital.com
 */
class Cti_Menubuilder_Model_Resource_Menu_Collection extends
    Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Method to instantiate the menu collection
     *
     * @return Cti_Menubuilder_Model_Resource_Menu_Collection
     */
    protected function _construct ()
    {
        $this->_init('cti_menubuilder/menu');
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * Add a filter for the stores the menu is associated to
     *
     * @param array $store     The store to filter on
     * @param bool  $withAdmin If the admin store should be included in the filter
     *
     * @return $this
     */
    public function addStoreFilter ($store, $withAdmin = true)
    {
        if ($store instanceof Mage_Core_Model_Store) {
            $store = array($store->getId());
        }

        if (!is_array($store)) {
            $store = array($store);
        }

        if ($withAdmin) {
            $store[] = Mage_Core_Model_App::ADMIN_STORE_ID;
        }

        $this->addFilter('store', array('in' => $store), 'public');

        return $this;
    }

    /**
     * Join the stores table if there is a store filter
     *
     * @return Mage_Core_Model_Resource_Db_Collection_Abstract
     */
    protected function _renderFiltersBefore ()
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                array(
                    'store_table' =>
                        $this->getTable('cti_menubuilder/menu_store')
                ),
                'main_table.menu_id = store_table.menu_id',
                array()
            )->group('main_table.menu_id');

            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }
        return parent::_renderFiltersBefore();
    }
}