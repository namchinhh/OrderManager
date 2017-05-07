<?php
/**
 * Created by PhpStorm.<?php
 */

namespace Magenest\OrderManager\Block\Adminhtml\Order\Edit\Tab;

/**
 * Class Address
 * @package Magenest\OrderManager\Block\Adminhtml\Order\Edit\Tab
 */
class Address extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magenest\OrderManager\Model\OrderManageFactory
     */
    protected $_orderFactory;

    /**
     * @var \Magenest\OrderManager\Model\OrderAddressFactory
     */
    protected $_addressFactory;

    /**
     * @var OrderFactory
     */
    protected $_ordercoreFactory;

    /**
     * @var \Magento\Directory\Model\RegionFactory
     */
    protected $_regionFactory;

    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    protected $_countryFactory;

    /**
     * @var \Magenest\OrderManager\Model\Connector
     */
    protected $_template = 'order/view/address_info.phtml';

    /**
     * @var \Magenest\OrderManager\Helper\Address
     */
    protected $_addressInfo;

    /**
     * Address constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magenest\OrderManager\Model\OrderAddressFactory $addressFactory
     * @param \Magento\Directory\Model\RegionFactory $regionFactory
     * @param \Magento\Directory\Model\CountryFactory $countryFactory
     * @param \Magento\Sales\Model\OrderFactory $ordercoreFactory
     * @param \Magenest\OrderManager\Model\OrderManageFactory $orderFactory
     * @param \Magenest\OrderManager\Helper\Address $addressInfo
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magenest\OrderManager\Model\OrderAddressFactory  $addressFactory,
        \Magento\Directory\Model\RegionFactory            $regionFactory,
        \Magento\Directory\Model\CountryFactory           $countryFactory,
        \Magento\Sales\Model\OrderFactory                 $ordercoreFactory,
        \Magenest\OrderManager\Model\OrderManageFactory   $orderFactory,
        \Magenest\OrderManager\Helper\Address             $addressInfo,
        array $data = []
    ) {
        $this->_orderFactory = $orderFactory;
        $this->_addressFactory   = $addressFactory;
        $this->_ordercoreFactory = $ordercoreFactory;
        $this->_regionFactory    = $regionFactory;
        $this->_countryFactory   = $countryFactory;
        $this->_addressInfo    = $addressInfo;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * get orderId
     *
     * @return mixed
     */
    public function getOrderId()
    {
        $id = $this->_coreRegistry->registry('id');
        $order = $this->_orderFactory->create()->getCollection()->addFieldToFilter('id', $id);
        foreach ($order as $orders) {
            $orderId = $orders->getOrderId();
            return $orderId;
        }
    }

    /**
     * return billing address
     * @return bool|string
     */
    public function getBillingAddress()
    {
        $orderId = $this->getOrderId();
        $model = $this->_addressFactory->create()->getCollection()
            ->addFieldToFilter('order_id', $orderId)
            ->addFieldToFilter('address_type', 'billing');

        if ($model->getData()) {
            $billing = $this->_addressInfo->getAddress($orderId, 'billing');
            return $billing;
        } else {
            return false;
        }
    }

    /**
     * return shipping address
     * @return bool|string
     */
    public function getShippingAddress()
    {
        $orderId = $this->getOrderId();
        $model = $this->_addressFactory->create()->getCollection()
            ->addFieldToFilter('order_id', $orderId)
            ->addFieldToFilter('address_type', 'shipping');

        if ($model->getData()) {
            $shipping = $this->_addressInfo->getAddress($orderId, 'shipping');
            return $shipping;
        } else {
            return false;
        }
    }

    /**
     * check status of order
     */
    public function getAccept(){
        $id = $this->getRequest()->getParam('id');
        $order = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\OrderManager\Model\OrderManage');
        $status=$order->load($id)->getStatusCheck();
        if( $status=="accept" ){
            return 1;
        }
        return 0;
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Address Information');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Address Information');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @param $order_id
     * @return string
     */
    public function getCustomBillingUrl($order_id)
    {
        return $this->getUrl('ordermanager/address/edit', ['order_id'=>$order_id, 'type'=>'billing']);
    }

    /**
     * @param $order_id
     * @return string
     */
    public function getCustomShippingUrl($order_id)
    {
        return $this->getUrl('ordermanager/address/edit', ['order_id'=>$order_id, 'type'=>'shipping']);
    }
    /**
     * @param $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
