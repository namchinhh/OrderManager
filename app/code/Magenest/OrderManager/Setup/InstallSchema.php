<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 *
 * Magenest_Blog extension
 * NOTICE OF LICENSE
 *
 * @category Magenest
 * @package  Magenest_Blog
 * @author   <ThaoPV> thaopw@gmail.com
 */
namespace Magenest\OrderManager\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 * @package Magenest\OrderManager\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)/
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        /**
         * Create table 'magenest_order_manager'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('magenest_order_manager'))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [   'identity' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )
            ->addColumn(
                'order_id',
                Table::TYPE_INTEGER,
                255,
                ['nullable' => false],
                'Order Id'
            )->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true],
                'Store Id'
            )
            ->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                255,
                ['nullable' => false],
                'Customer Id'
            )
            ->addColumn(
                'status',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Status'
            )
            ->addColumn(
                'status_check',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Status Check'
            )
            ->addColumn(
                'create_at',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Creat At'
            )
            ->addColumn(
                'customer_name',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Customer Name'
            )
            ->addColumn(
                'customer_email',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Customer Email'
            )
            ->setComment('Magenest Order Manager Items');
            $installer->getConnection()->createTable($table);
        /**
         * Create table 'magenest_order_manager_item'
         */
            $table = $installer->getConnection()->newTable(
                $installer->getTable('magenest_order_manager_item')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )->addColumn(
                'order_id',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Order Id'
            )->addColumn(
                'product_id',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Product Id'
            )->addColumn(
                'thumbnail',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Thumbnail'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                100,
                ['nullable' => true],
                'Name'
            )->addColumn(
                'sku',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Sku'
            )->addColumn(
                'price',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Price'
            )->addColumn(
                'quantity',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Quantity'
            )->addColumn(
                'discount',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Discount'
            )->addColumn(
                'tax',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Tax'
            )->addColumn(
                'color',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Color'
            )->addColumn(
                'size',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Size'
            )->addColumn(
                'type',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Type'
            )->setComment(
                'Magenest Order Manager Items'
            );
            $installer->getConnection()->createTable($table);


        /**
         * Create table 'magenest_order_manager_address'
         */
            $table = $installer->getConnection()
            ->newTable($installer->getTable('magenest_order_manager_address'))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [   'identity' => true, 'nullable' => false, 'primary' => true,],
                'Id'
            )
            ->addColumn(
                'address_id',
                Table::TYPE_SMALLINT,
                10,
                ['nullable' => false],
                'Address Id'
            )
            ->addColumn(
                'order_id',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Order Id'
            )
            ->addColumn(
                'region_id',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Region Id'
            )
            ->addColumn(
                'country_id',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Country Id'
            )
            ->addColumn(
                'postcode',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Postcode'
            )
            ->addColumn(
                'fax',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Fax'
            )
            ->addColumn(
                'lastname',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Lastname'
            )
            ->addColumn(
                'firstname',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Firstname'
            )
            ->addColumn(
                'street',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Street'
            )
            ->addColumn(
                'city',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'City'
            )
            ->addColumn(
                'telephone',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Phone Number'
            )
            ->addColumn(
                'company',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Company'
            )->addColumn(
                'address_type',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Address Type'
            )->setComment('Magenest Order Manager Address');

            $installer->getConnection()->createTable($table);

            $installer->endSetup();
    }
}
