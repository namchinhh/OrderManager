<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\OrderManager\Block\Product;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class View
 * @package Magenest\OrderManager\Block\Product
 */
class View extends Template
{
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var CustomerSession\
     */
    protected $_customerSession;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderCollection;

    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     */
    protected $_stockFactory;

    /**
     * @var \Magenest\OrderManager\Model\OrderItemFactory
     */
    protected $_itemFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    /**
     * View constructor.
     * @param Context $context
     * @param CustomerSession $customerSession
     * @param ScopeConfigInterface $scopeConfig
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magenest\OrderManager\Model\OrderItemFactory $itemFactory
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockFactory
     * @param StoreManagerInterface $storemanager
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CustomerSession $customerSession,
        ScopeConfigInterface $scopeConfig,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magenest\OrderManager\Model\OrderItemFactory $itemFactory,
        \Magento\CatalogInventory\Api\StockStateInterface $stockFactory,
        StoreManagerInterface $storemanager,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_stockFactory = $stockFactory;
        $this->_storeManager = $storemanager;
        $this->_customerSession = $customerSession;
        $this->_scopeConfig = $scopeConfig;
        $this->_orderCollection = $orderFactory;
        $this->_itemFactory = $itemFactory;
        parent::__construct($context, $data);
    }

    /**
     * return string
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__(' Edit Product(s)'));
    }

    /**
     * @return mixed
     */
    public function getOrderd()
    {
        $orderId = $this->_coreRegistry->registry('order_id');

        return $orderId;
    }
    /**
     * get data product on store
     * @return $this
     */
    public function getItems()
    {
        $orderId = $this->getOrderd();
        $collectionItem = $this->_orderCollection->create()->load($orderId);

        return $collectionItem;
    }

    /**
     * get item order edit
     * @return mixed
     */
    public function getNewProduct()
    {
        $orderId = $this->getOrderd();
        $data = $this->_itemFactory->create()->getCollection()->addFieldToFilter('order_id', $orderId);

        return $data;
    }

    /**
     * @param $id_product
     * @return string
     */
    public function getRemoveProduct($id_product)
    {
        $orderId = $this->getOrderd();

        return $this->getUrl('ordermanager/product/remove', ['item_id'=>$id_product, 'order_id'=>$orderId]);
    }

    /**
     * @return string
     */
    public function getNewAddProductUrl()
    {
        $orderId = $this->getOrderd();

        return $this->getUrl('ordermanager/product/save', ['order_id' => $orderId]);
    }

    /**
     * @return string
     */
    public function getUpdateProductUrl()
    {
        $orderId = $this->getOrderd();

        return $this->getUrl('ordermanager/product/update', ['order_id'=>$orderId, 'type'=>'update']);
    }

    /**
     * @return string
     */
    public function getUpdateBackUrl()
    {
        $orderId = $this->getOrderd();

        return $this->getUrl('ordermanager/product/update', ['order_id'=>$orderId, 'type'=>'back']);
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {

        return $this->getChildHtml('pager');
    }

    /**
     * get total order edit
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSubtotal()
    {
        $subtotal = $this->getLayout()->createBlock('Magenest\OrderManager\Block\Product\Subtotal\Total');

        return $subtotal->toHtml();
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        $orderId = $this->getOrderd();

        return $this->getUrl('sales/order/view', ['order_id'=>$orderId]);
    }

    /**
     * return form new product
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductNew()
    {
        $newProduct = $this->getLayout()->createBlock('Magenest\OrderManager\Block\Product\NewProduct');

        return $newProduct->toHtml();
    }

    /**
     * get stock product
     * @return \Magento\CatalogInventory\Api\StockStateInterface
     */
    public function getStockProduct()
    {
        $quantity = $this->_stockFactory;

        return $quantity;
    }

    /**
     * @return string
     */
    public function getAddProductUrl()
    {
        $orderId = $this->getOrderd();

        return $this->getUrl('ordermanager/product/add', ['order_id' => $orderId]);
    }
}
