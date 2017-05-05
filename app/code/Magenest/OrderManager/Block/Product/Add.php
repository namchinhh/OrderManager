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
 * Class AddProduct
 * @package Magenest\OrderManager\Block\Product
 */
class Add extends Template
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
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productFactory;

    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     */
    protected $_stockFactory;

    /**
     * @var
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Catalog\Model\Config
     */
    protected $_catalogConfig;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * Add constructor.
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
        Template\Context $context,
        CustomerSession $customerSession,
        ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\CatalogInventory\Api\StockStateInterface $stockFactory,
        \Magento\Catalog\Model\Config $catalogConfig,
        StoreManagerInterface $storemanager,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_catalogConfig = $catalogConfig;
        $this->_stockFactory = $stockFactory;
        $this->_storeManager = $storemanager;
        $this->_customerSession = $customerSession;
        $this->_scopeConfig = $scopeConfig;
        $this->_orderCollection = $orderFactory;
        $this->_productFactory = $productFactory;
        parent::__construct($context, $data);

        //get collection of data
        $attributes = $this->_catalogConfig->getProductAttributes();
        $collection = $this->_productFactory->create()->getCollection();
        $collection->addAttributeToSelect(
            $attributes
        )->addAttributeToSelect(
            'sku'
        );

        $this->setCollection($collection);
        $this->pageConfig->getTitle()->set(__('Add Product(s)'));
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getCollection()) {
            // create pager block for collection
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'webkul.grid.record.pager'
            )->setCollection(
                $this->getCollection() // assign collection to pager
            );
            $this->setChild('pager', $pager);// set pager block in layout
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        $orderId = $this->_coreRegistry->registry('order_id');

        return $orderId;
    }

    /**
     * @return string
     */
    public function getNewAddProductUrl()
    {
        $orderId = $this->getOrderId();

        return $this->getUrl('ordermanager/product/save', ['order_id' => $orderId]);
    }

    /**
     * @return mixed
     */
    public function getImageRender()
    {
        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );

        return $mediaDirectory;
    }

    /**
     * @return \Magento\CatalogInventory\Api\StockStateInterface
     */
    public function getStockProduct()
    {
        $quantity = $this->_stockFactory;

        return $quantity;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSearchUrl()
    {
        $orderId = $this->getOrderId();

        return $this->getUrl('ordermanager/product/search', ['order_id'=>$orderId]);
    }
    /**
     * @return string
     */
    public function getAddProductUrl()
    {
        $orderId = $this->getOrderId();

        return $this->getUrl('ordermanager/product/addProduct', ['order_id' => $orderId]);
    }

    /**
     * symbol of currency
     *
     * @return string
     */
    public function getSymbolCurrency()
    {
        $orderId = $this->getOrderId();
        $collectionItem = $this->_orderCollection->create()->load($orderId);
        $symbol = $collectionItem->getOrderCurrency()->getCurrencySymbol();

        return $symbol;
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        $orderId = $this->getOrderId();

        return $this->getUrl('ordermanager/product/view', ['order_id'=>$orderId]);
    }
}
