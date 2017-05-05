<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\OrderManager\Block\Order;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magenest\OrderManager\Model\OrderManageFactory ;
use Magenest\OrderManager\Model\OrderItemFactory ;
use Magenest\OrderManager\Helper\Address ;
use Magenest\OrderManager\Helper\Total ;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Sales\Model\OrderFactory;

/**
 * Class History
 *
 * @package Magenest\Ticket\Block\Order
 */
class View extends Template
{
    /**
     * @var OrderItemFactory
     */
    protected $_itemFactory;
    /**
     * @var Address
     */
    protected $_addressInfo;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var string
     */
    protected $_totalInfo;

    /**
     * @var \Magenest\OrderManager\Model\OrderAddressFactory
     */
    protected $_addressFactory;

    /**
     * ViewManage constructor.
     * @param Context $context
     * @param OrderItemFactory $itemFactory
     * @param Address $addressInfo
     * @param CustomerSession $customerSession
     * @param OrderFactory $ordercoreFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param Total $totalInfo
     * @param \Magento\Directory\Model\RegionFactory $regionFactory
     * @param \Magenest\OrderManager\Model\OrderAddressFactory $orderAddressFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        OrderItemFactory   $itemFactory,
        Address            $address,
        CustomerSession $customerSession,
        OrderFactory $ordercoreFactory,
        ScopeConfigInterface $scopeConfig,
        Total $totalInfo,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        \Magenest\OrderManager\Model\OrderAddressFactory $addressFactory,
        array $data = []
    ) {

        $this->_totalInfo = $totalInfo;
        $this->_regionFactory    = $regionFactory;
        $this->_itemFactory        = $itemFactory;
        $this->_addressInfo     = $address;
        $this->_ordercoreFactory = $ordercoreFactory;
        $this->_customerSession = $customerSession;
        $this->_scopeConfig = $scopeConfig;
        $this->_addressFactory = $addressFactory;

        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('Information'));
    }

    /**
     * Get Ticket Collection
     *
     * @return bool|\Magento\Sales\Model\ResourceModel\Order\Collection
     */
    public function getOrderId()
    {
        $orderId = $this->getRequest()->getParam('order_id');

        return $orderId;
    }
    /**
     *  infomation product of order
     */
    public function getItemsInfo()
    {
        $orderId = $this->getOrderId();
        $items   = $this->_itemFactory->create()->getCollection()->addFieldToFilter('order_id', $orderId);

        return $items;
    }
    /**cd
     * currency
     * @return string
     */
    public function getSymbolItem()
    {
        $orderId = $this->getOrderId();
        $data = $this->_ordercoreFactory->create()->load($orderId);
        $symbol = $data->getOrderCurrency()->getCurrencySymbol();

        return $symbol;
    }

    /**
     * getData billing
     *
     * @return bool|string
     */
    public function getBillingAddress()
    {
        $orderId = $this->getOrderId();
        $model = $this->_addressFactory->create()->getCollection()
            ->addFieldToFilter('order_id', $orderId)->addFieldToFilter('address_type', 'billing');

        if ($model->getData()) {
            $billing = $this->_addressInfo->getAddress($orderId, 'billing');

            return $billing;
        } else {
            return false;
        }
    }


    /**
     * getData shipping
     *
     * @return bool|string
     */
    public function getShippingAddress()
    {
        $orderId = $this->getOrderId();
        $model = $this->_addressFactory->create()->getCollection()
            ->addFieldToFilter('order_id', $orderId)->addFieldToFilter('address_type', 'shipping');

        if ($model->getData()) {
            $shipping = $this->_addressInfo->getAddress($orderId, 'shipping');
            return $shipping;
        } else {
            return false;
        }
    }

    /**
     * @return bool|string
     */
    public function getTotal()
    {
        if ($this->getItemsInfo()->getData()) {
             $orderId = $this->getOrderId();
             $data = $this->_totalInfo->getTotalData($orderId);
             return $data;
        } else {
             return false;
        }
    }
    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @param $orderId
     * @return string
     */
    public function getViewUrl($orderId)
    {
        return $this->getUrl('ordermanager/order/view', ['order_id' => $orderId]);
    }
}
