<?php
/**
 * Created by PhpStorm.
 * User: gialam
 * Date: 25/01/2016
 * Time: 20:24
 */
namespace Magenest\OrderManager\Block\Adminhtml\Order\System;

/**
 * Class NewItem
 * @package Magenest\OrderManager\Block\Adminhtml\Order\System\Edit
 */
class NewItem extends \Magento\Backend\Block\Template
{
    /**
     * @var string
     */
    protected $_template = 'order/item.phtml';

    /**
     * @var \Magenest\OrderManager\Model\OrderItemFactory
     */
    protected $_itemFactory;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderCollection;

    /**
     * NewItem constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magenest\OrderManager\Model\OrderItemFactory $itemFactory
     * @param \Psr\Log\LoggerInterface $loggerInterface
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magenest\OrderManager\Model\OrderItemFactory $itemFactory,
        \Psr\Log\LoggerInterface $loggerInterface,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        array $data = []
    ) {
        $this->_orderCollection = $orderFactory;
        $this->_logger = $loggerInterface;
        $this->_itemFactory = $itemFactory;
        parent::__construct($context, $data);
    }


    /**
     * @return mixed
     */
    public function getOrderId()
    {
        $order_id = $this->getRequest()->getParam('order_id');
        
        return $order_id;
    }

    /**
     * @return $this
     */
    public function getCollection()
    {
        $order_id = $this->getOrderId();
        $model = $this->_itemFactory->create()->getCollection()
            ->addFieldToFilter('order_id',$order_id);
        
        return $model;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        $symbol = $this->_orderCollection->create()->getOrderCurrency()->getCurrencySymbol();
       
        return $symbol;
    }

    /**
     * @return string
     */
    public function getUpdateUrl()
    {
        return $this->getUrl('ordermanager/order_system/update', ['order_id'=>$this->getOrderId()]); 
    }

    /**
     * @param $itemId
     * @return string
     */
    public function getRemove($itemId)
    {
        return $this->getUrl('ordermanager/order_system/remove', ['order_id'=>$this->getOrderId(), 'item_id'=>$itemId]);
    }

    /**
     * @return string
     */
    public function getSubmit()
    {
        return $this->getUrl('ordermanager/order_system/submit', ['order_id'=>$this->getOrderId(), '_current' => true]);
    }
}
