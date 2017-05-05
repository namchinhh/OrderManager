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
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class History
 *
 * @package Magenest\Ticket\Block\Order
 */
class History extends Template
{
    /**
     * Template
     *
     * @var string
     */
    protected $_template = 'order/history.phtml';


    /**
     * @var OrderManageFactory
     */
    protected $_manageFactory;

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
    protected $tickets;

    /**
     * @param Context $context
     * @param CustomerSession $customerSession
     * @param ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        OrderManageFactory $manageFactory,
        CustomerSession $customerSession,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->_manageFactory = $manageFactory;
        $this->_customerSession = $customerSession;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('My Order'));
    }

    /**
     * Get Ticket Collection
     *
     * @return bool|\Magento\Sales\Model\ResourceModel\Order\Collection
     */
    public function getOrderInfo()
    {
        if (!($customerId = $this->_customerSession->getCustomerId())) {
            return false;
        } else {
            $data = $this->_manageFactory->create()->getCollection()
            ->addFieldToFilter('customer_id', $customerId);
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * return link view order
     *
     * @param $orderId
     * @return string
     */
    public function getViewUrl($orderId)
    {
        return $this->getUrl('ordermanager/order/view', ['order_id' => $orderId]);
    }

    /**
     * return controller delete order
     * @param $orderId
     * @return string
     */
    public function getDeleteUrl($orderId)
    {
        return $this->getUrl('ordermanager/order/delete', ['order_id' => $orderId]);
    }
}
