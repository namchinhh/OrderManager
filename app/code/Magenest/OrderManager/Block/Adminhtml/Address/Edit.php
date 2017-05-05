<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderManager\Block\Adminhtml\Address;

use Magento\Directory\Block\Data;
use Magento\Customer\Block\Address\Edit as editData;

/**
 * Class Edit
 * @package Magenest\OrderManager\Block\Adminhtml\Address\Billing
 */
class Edit extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magenest\OrderManager\Model\OrderAddressFactory
     */
    protected $_addressFactory;

    /**
     * @var Data
     */
    protected $_country;

    /**
     * @var \Magento\Directory\Model\RegionFactory
     */
    protected $_regionFactory;

    /**
     * @var editData
     */
    protected $_editData;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magenest\OrderManager\Model\OrderManageFactory
     */
    protected $_manageFactory;
    
    /**
     * Edit constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magenest\OrderManager\Model\OrderAddressFactory $addressFactory
     * @param \Magenest\OrderManager\Model\OrderManageFactory $manageFactory
     * @param \Magento\Directory\Model\RegionFactory $regionFactory
     * @param Data $collectionDataShipping
     * @param editData $editData
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magenest\OrderManager\Model\OrderAddressFactory $addressFactory,
        \Magenest\OrderManager\Model\OrderManageFactory $manageFactory,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        \Magento\Framework\Registry $registry,
        \Psr\Log\LoggerInterface $loggerInterface,
        Data  $country,
        editData $editData,
        array $data = []
    ) {
        $this->_logger = $loggerInterface;
        $this->_coreRegistry        = $registry;
        $this->_addressFactory = $addressFactory;
        $this->_manageFactory  = $manageFactory;
        $this->_country = $country;
        $this->_regionFactory = $regionFactory;
        $this->_editData = $editData;
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
     *
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
     * @return mixed
     */
    public function getAddress()
    {
        $orderId = $this->getOrderId();
        $type= $this->getType();
        if ($type == 'billing') {
            $data = $this->_addressFactory->create()->getCollection()
                ->addFieldToFilter('order_id', $orderId)
                ->addFieldToFilter('address_type', 'billing');
        } else {
            $data = $this->_addressFactory->create()->getCollection()
                ->addFieldToFilter('order_id', $orderId)
                ->addFieldToFilter('address_type', 'shipping');
        }

        return $data;
    }

    /**
     * @return mixed
     */
    public function getRegionId()
    {
        $collection = $this->getAddress();
        foreach ($collection as $collections) {
            $region = $collections->getRegionId();
        }

        return $region;
    }

    /**
     * @return string
     */
    public function getRegionName()
    {
        $id = $this->getRegionId();
        if ($id) {
            $collection = $this->_regionFactory->create()->load($id, 'region_id')->getName();
            return $collection;
        } else {
            return false;
        }
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
     * @return string
     */
    public function getSaveUrl()
    {
        $orderId = $this->getOrderId();
        $type= $this->getType();
        if ($type == 'billing') {
            return $this->getUrl('ordermanager/address/save', ['order_id' => $orderId, 'type' => 'billing']);
        } else {
            return $this->getUrl('ordermanager/address/save', ['order_id' => $orderId, 'type' => 'shipping']);
        }
    }
}
