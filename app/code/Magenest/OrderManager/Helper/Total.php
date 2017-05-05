<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\OrderManager\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

/**
 * Class Total
 * @package Magenest\OrderManager\Helper
 */
class Total extends AbstractHelper
{
    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_itemFactory;

    /**
     * @var \Magenest\OrderManager\Model\OrderAddressFactory
     */
    protected $_addressFactory;

    /**
     * @var \Magenest\OrderManager\Model\OrderManageFactory
     */
    protected $_manageFactory;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderCollection;
    
    /**
     * Total constructor.
     * @param Context $context
     * @param \Magenest\OrderManager\Model\OrderItemFactory $itemFactory
     * @param \Magenest\OrderManager\Model\OrderAddressFactory $addressFactory
     * @param \Magenest\OrderManager\Model\OrderManageFactory $manageFactory
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        \Magenest\OrderManager\Model\OrderItemFactory    $itemFactory,
        \Magenest\OrderManager\Model\OrderAddressFactory $addressFactory,
        \Magenest\OrderManager\Model\OrderManageFactory  $manageFactory,
        \Magento\Sales\Model\OrderFactory                $orderFactory,
        array $data = []
    ) {
        $this->_orderCollection        = $orderFactory;
        $this->_itemFactory            = $itemFactory;
        $this->_addressFactory         = $addressFactory;
        $this->_manageFactory          = $manageFactory;
        parent::__construct($context);
    }


    /**
     * @param $creditmemoId
     * @return bool|string
     * @throws \Zend_Pdf_Exception
     */
    public function getTotalData($orderId)
    {
        $modelOrder = $this->_orderCollection->create()->load($orderId);
        $priceShipping = $modelOrder->getShippingAmount();
        $symbol = $modelOrder->getOrderCurrency()->getCurrencySymbol();
        $collection = $this->_itemFactory->create()->getCollection()->addFieldToFilter('order_id', $orderId);
        $subtotals = 0;
        $discounts = 0;
        $taxs      = 0;
        $i = 0;
        /** @var \Magenest\OrderManager\Model\OrderItemFactory $collections */
        foreach ($collection as $collections) {
            $tax     = $collections->getTax();
            $discount = $collections->getDiscount();
            $price = $collections->getPrice();
            $quantity = $collections->getQuantity();
            $subtotal = $price * $quantity;
            $rowTotal    = $price * $quantity ;
            $rowDiscount = ($rowTotal * $discount)/100 ;
            $i++;
            $discounts += $rowDiscount;
            $subtotals += $subtotal;
            $taxs      += $tax;
        }

        $grandTotal = $subtotals + $priceShipping + $taxs - $discounts;

        $dataTotal = [
            'discount'         =>$discounts,
            'subtotal'         =>$subtotals,
            'shipping_handling'=>$priceShipping,
            'grandtotal'       =>$grandTotal,
            'tax'              =>$taxs,
            'symbol'           => $symbol,
        ];

        return $dataTotal;
    }
}
