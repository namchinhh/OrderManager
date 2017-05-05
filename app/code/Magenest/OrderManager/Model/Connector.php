<?php
/**
 * Created by PhpStorm.
 * User: gialam
 * Date: 01/03/2016
 * Time: 09:59
 */
namespace Magenest\OrderManager\Model;

/**
 * Class Connector
 * @package Magenest\OrderManager\Model
 */
class Connector
{
    const CONFIG_DELETE_ORDER_ADMIN = 'ordermanager_labels/ordermanager_editor/ordermanager_delete_core';
    const CONFIG_REMOVE_ITEM_ADMIN = 'ordermanager_labels/ordermanager_editor/ordermanager_remove';
    const CONFIG_EDIT_ITEM_ADMIN = 'ordermanager_labels/ordermanager_editor/ordermanager_edit_item';
    const CONFIG_EDIT_ITEM_CUSTOMER = 'ordermanager_labels/ordermanager_customer/ordermanager_enable_item';
    const CONFIG_EDIT_SHIPPING_CUSTOMER = 'ordermanager_labels/ordermanager_customer/ordermanager_enable_shipping';
    const CONFIG_EDIT_BILLING_CUSTOMER = 'ordermanager_labels/ordermanager_customer/ordermanager_enable_billing';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Connector constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @return mixed
     */
    public function getDeleteOrder()
    {
        $orderDelete =  $this->_scopeConfig->getValue(self::CONFIG_DELETE_ORDER_ADMIN, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        return $orderDelete;
    }

    /**
     * @return mixed
     */
    public function getRemoveItem()
    {
        $orderRemove =  $this->_scopeConfig->getValue(self::CONFIG_REMOVE_ITEM_ADMIN, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        return $orderRemove;
    }

    /**
     * @return mixed
     */
    public function getEditItem()
    {
        $orderEdit =  $this->_scopeConfig->getValue(self::CONFIG_EDIT_ITEM_ADMIN, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $orderEdit;
    }

    /**
     * @return mixed
     */
    public function getEnableItem()
    {
        $editItem =  $this->_scopeConfig->getValue(self::CONFIG_EDIT_ITEM_CUSTOMER, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        return $editItem;
    }

    /**
     * @return mixed
     */
    public function getEnableShipping()
    {
        $editShipping =  $this->_scopeConfig->getValue(self::CONFIG_EDIT_SHIPPING_CUSTOMER, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        return $editShipping;
    }

    /**
     * @return mixed
     */
    public function getEnableBilling()
    {
        $editBilling =  $this->_scopeConfig->getValue(self::CONFIG_EDIT_BILLING_CUSTOMER, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        return $editBilling;
    }
}
