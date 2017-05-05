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
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class NewProduct
 * @package Magenest\OrderManager\Block\Product
 */
class NewProduct extends Template
{
    /**
     * @var string
     */
    protected $_template = 'order/product/newproduct.phtml';

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_itemFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productFactory;

    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     */
    protected $_stockFactory;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderCollection;

    /**
     * NewProduct constructor.
     * @param Context $context
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magenest\OrderManager\Model\OrderItemFactory $itemFactory
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockFactory
     * @param StoreManagerInterface $storemanager
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magenest\OrderManager\Model\OrderItemFactory $itemFactory,
        \Magento\CatalogInventory\Api\StockStateInterface $stockFactory,
        StoreManagerInterface $storemanager,
        array $data = []
    ) {
        $this->_stockFactory = $stockFactory;
        $this->_storeManager = $storemanager;
        $this->_orderCollection = $orderFactory;
        $this->_itemFactory = $itemFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getSymbolCurrency()
    {
        $orderId        = $this->getRequest()->getParam('order_id');
        $collectionItem = $this->_orderCollection->create()->load($orderId);
        $symbol         = $collectionItem->getOrderCurrency()->getCurrencySymbol();

        return $symbol;
    }

    /**
     * @return mixed
     */
    public function getNewProduct()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        $data    = $this->_itemFactory->create()->getCollection()
            ->addFieldToFilter('order_id', $orderId);

        return $data;
    }

    /**
     * remove product add
     * @param $id
     * @return string
     */
    public function getRemoveProduct($id)
    {
        $orderId   = $this->getRequest()->getParam('order_id');

        return $this->getUrl('ordermanager/product/remove', ['item_id'=>$id, 'order_id'=>$orderId]);
    }

    /**
     * @return string
     */
    public function getCancelUrl()
    {
        $orderId = $this->getRequest()->getParam('order_id');

        return $this->getUrl('ordermanager/product/cancel', ['order_id'=>$orderId]);
    }

    /**
     * @return string
     */
    public function getUpdateQuantityUrl()
    {
        $orderId   = $this->getRequest()->getParam('order_id');

        return $this->getUrl('ordermanager/product/updateItem', ['order_id'=>$orderId]);
    }

    /**
     * @return \Magento\CatalogInventory\Api\StockStateInterface
     */
    public function getStockProduct()
    {
        $quantity = $this->_stockFactory;

        return $quantity;
    }
}
