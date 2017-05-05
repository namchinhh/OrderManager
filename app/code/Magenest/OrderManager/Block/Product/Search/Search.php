<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\OrderManager\Block\Product\Search;

use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session as CustomerSession;
use Magenest\OrderManager\Block\Product\Add;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Search
 * @package Magenest\OrderManager\Block\Product\Search
 */
class Search extends Add
{
    /**
     * Search constructor.
     * @param Context $context
     * @param CustomerSession $customerSession
     * @param ScopeConfigInterface $scopeConfig
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockFactory
     * @param \Magento\Catalog\Model\Config $catalogConfig
     * @param StoreManagerInterface $storemanager
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\CatalogInventory\Api\StockStateInterface $stockFactory,
        \Magento\Catalog\Model\Config $catalogConfig,
        StoreManagerInterface $storemanager,
        \Magento\Framework\Registry $registry,
        array $data
    ) {
        parent::__construct($context, $customerSession, $scopeConfig, $productFactory, $orderFactory, $stockFactory, $catalogConfig, $storemanager, $registry, $data);
        //get collection of data
        $name = $this->_coreRegistry->registry('name');
        $attributes = $this->_catalogConfig->getProductAttributes();
        $collection = $this->_productFactory->create()->getCollection();
        $collection->addAttributeToSelect(
            $attributes
        )->addAttributeToSelect(
            'sku'
        )->addAttributeToFilter('name', array('like'=>'%'.$name.'%'));
        $this->setCollection($collection);
    }

    /**
     * @return bool
     */
    public function getPagerHtml()
    {
        return false;
    }
}
