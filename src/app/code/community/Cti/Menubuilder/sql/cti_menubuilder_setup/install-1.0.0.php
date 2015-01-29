<?php
/**
 * Installer for Menu Builder.
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
 * @var $this Mage_Eav_Model_Entity_Setup
 */
$installer = $this;

$installer->startSetup();

try {
    // Create the cti_menubuilder_menu table
    $tableName = $installer->getTable('cti_menubuilder/menu');

    // Drop the table if it exists
    if ($installer->getConnection()->isTableExists($tableName)) {
        $installer->getConnection()->dropTable($tableName);
    }

    // Create the table for storing menus
    $table = $installer->getConnection()->newTable($tableName)
        ->addColumn(
            'menu_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
                'identity' => true,
            ),
            'Menu ID'
        )->addColumn(
            'name',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            255,
            array(
                'nullable' => false,
            ),
            'Menu Name'
        )->addColumn(
            'identifier',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            255,
            array(
                'nullable' => false,
            ),
            'Menu Identifier'
        )->addColumn(
            'created_at',
            Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
            null,
            array(
                'default'   => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
            ),
            'The datetime the menu was created.'
        )->addColumn(
            'updated_at',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(),
            'The datetime the menu was updated.'
        );

    $installer->getConnection()->createTable($table);

    // Create the cti_menubuilder_menu_store table
    $tableName = $installer->getTable('cti_menubuilder/menu_store');

    // Drop the table if it exists
    if ($installer->getConnection()->isTableExists($tableName)) {
        $installer->getConnection()->dropTable($tableName);
    }

    // Create the table for associating menus to stores
    $table = $installer->getConnection()->newTable($tableName)
        ->addColumn(
            'menu_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'unsigned'  => true,
                'nullable'  => false,
            ),
            'Menu ID'
        )->addColumn(
            'store_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
            ),
            'Store ID'
        );

    $installer->getConnection()->createTable($table);

    // Create the cti_menubuilder_menu_item table
    $tableName = $installer->getTable('cti_menubuilder/menu_item');

    // Drop the table if it exists
    if ($installer->getConnection()->isTableExists($tableName)) {
        $installer->getConnection()->dropTable($tableName);
    }

    // Create the table for storing menu items
    $table = $installer->getConnection()->newTable($tableName)
        ->addColumn(
            'item_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'identity'  => true,
            ),
            'Item ID'
        )->addColumn(
            'menu_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'unsigned'  => true,
                'nullable'  => false,
            ),
            'Menu ID'
        )->addColumn(
            'name',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            255,
            array(),
            'Item Name'
        )->addColumn(
            'level',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'unsigned'  => true,
                'nullable'  => false,
            ),
            'Item Level'
        )->addColumn(
            'position',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'unsigned'  => true,
                'nullable'  => false,
            ),
            'Item Position'
        )->addColumn(
            'parent_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'unsigned'  => true,
                'nullable'  => false,
            ),
            'Parent ID'
        );

    $installer->getConnection()->createTable($table);
} catch (Exception $e) {
    Mage::logException($e);
}

$installer->endSetup();