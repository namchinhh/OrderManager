<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\OrderManager\Block\Address;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Directory\Block\Data;
use Magento\Customer\Block\Address\Edit as editData;

/**
 * Class Edit
 * @package Magenest\OrderManager\Block\Address\Billing
 */
class Edit extends Template
{
    /**
     * @var CustomerSession
     */
    protected $_customerSession;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $itemCollection;

    /**
     * @var Data
     */
    protected $_country;

    /**
     * @var editData
     */
    protected $_editData;

    /**
     * @var \Magenest\OrderManager\Model\OrderAddressFactory
     */
    protected $_addressFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Edit constructor.
     * @param Context $context
     * @param CustomerSession $customerSession
     * @param ScopeConfigInterface $scopeConfig
     * @param \Magento\Sales\Model\OrderFactory $ItemCollectionFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CustomerSession $customerSession,
        ScopeConfigInterface $scopeConfig,
        \Magento\Sales\Model\OrderFactory $ItemCollectionFactory,
        \Magenest\OrderManager\Model\OrderAddressFactory $addressFactory,
        Data $country,
        editData $editData,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_customerSession = $customerSession;
        $this->_scopeConfig = $scopeConfig;
        $this->itemCollection        = $ItemCollectionFactory;
        $this->_country = $country;
        $this->_editData = $editData;
        $this->_addressFactory  = $addressFactory;
        $this->_coreRegistry        = $registry;
        parent::__construct($context, $data);
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
     * @return mixed
     */
    public function getType()
    {
        $type = $this->_coreRegistry->registry('type');

        return $type;
    }

    /**
     * return header
     */
    protected function _construct()
    {
        parent::_construct();
        $type= $this->getType();
        if ($type == 'billing') {
            $this->pageConfig->getTitle()->set(__('Billing Information '));
        } else {
            $this->pageConfig->getTitle()->set(__('Shipping Information '));
        }
    }

    /**
     * @return $this
     */
    public function getAddress()
    {
        $orderId = $this->getOrderId();
        $collectionItem = $this->itemCollection->create()->load($orderId);

        return $collectionItem;
    }

    /**
     * @return mixed
     */
    public function getShippingAddress()
    {
        $orderId = $this->getOrderId();
        $data = $this->_addressFactory->create()->getCollection()
            ->addFieldToFilter('order_id', $orderId)
            ->addFieldToFilter('address_type', 'shipping')
            ->getData();

        return $data;
    }

    /**
     * @return mixed
     */
    public function getBillingAddress()
    {
        $orderId = $this->getOrderId();
        $data = $this->_addressFactory->create()->getCollection()
            ->addFieldToFilter('order_id', $orderId)
            ->addFieldToFilter('address_type', 'billing')
            ->getData();

        return $data;
    }

    /**
     * @return string
     */
    public function getCountryHtmlSelect()
    {
        $country = $this->_country->getCountryHtmlSelect();

        return $country;
    }

    /**
     * save action
     *
     * @return string
     *
     */
    public function getBaseUrl()
    {
        $orderId =  $this->getOrderId();
        $type= $this->getType();
        if ($type == 'billing') {
            return $this->getUrl('ordermanager/address/save', ['order_id' => $orderId, 'type' => 'billing']);
        } else {
            return $this->getUrl('ordermanager/address/save', ['order_id' => $orderId, 'type' => 'shipping']);
        }
    }
}
