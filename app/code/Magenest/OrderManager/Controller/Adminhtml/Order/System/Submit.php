<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderManager\Controller\Adminhtml\Order\System;

use Magento\Backend\App\Action\Context;
use Magenest\OrderManager\Model\OrderItemFactory;
use Magento\Directory\Model\RegionFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\Registry;

/**
 * Class Submit
 * @package Magenest\OrderManager\Controller\Adminhtml\Order\System
 */
class Submit extends  \Magento\Backend\App\Action
{
    /**
     * @var
     */
    protected $_request;

    /**
     * @var OrderItemFactory
     */
    protected $_itemFactory;

    /**
     * @var RegionFactory
     */
    protected $_regionFactory;

    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * Accept constructor.
     * @param Context $context
     * @param OrderItemFactory $itemFactory
     * @param RegionFactory $regionFactory
     */
    public function __construct(
        Context                $context,
        OrderItemFactory       $itemFactory,
        RegionFactory          $regionFactory,
        LoggerInterface        $loggerInterface,
        Registry               $registry
    ) {
        $this->_logger         = $loggerInterface;
        $this->_itemFactory    = $itemFactory;
        $this->_regionFactory  = $regionFactory;
        $this->_coreRegistry       = $registry;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $orderId = $this->getRequest()->getParam('order_id');
//        $this->_logger->debug(print_r($id,true));
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $modelOrder       = $this->_objectManager->create('Magento\Sales\Model\Order');
        $modelItem        = $this->_objectManager->create('Magento\Sales\Model\Order\Item');
        $items            = $this->_itemFactory->create()->getCollection()
                            ->addFieldToFilter('order_id',$orderId);
        $subtotalOld = $modelOrder->load($orderId,'entity_id')->getSubtotal();
        $this->_logger->debug($subtotalOld);
        $totals = 0;
        $i = 0;
        try {
            /* save item */
            if(!empty($items->getData())) {
                foreach ($items as $item) {
                    $productId     = $item['product_id'];
                    $dataItem = [
                        'order_id' => $orderId,
                        'product_id'=>$item['product_id'],
                        'name'     => $item['name'],
                        'sku'      => $item['sku'],
                        'original_price' => $item['price'],
                        'price'    => $item['price'],
                        'discount_percent'       => $item['discount'],
                        'discount_amount'        => $item['discount'] * $item['quantity'] * $item['price'] / 100,
                        'base_discount_amount'   => $item['discount'] * $item['quantity'] * $item['price'] / 100,
                        'discount_invoiced'      => $item['discount'] * $item['quantity'] * $item['price'] / 100,
                        'base_discount_invoiced' => $item['discount'] * $item['quantity'] * $item['price'] / 100,
                        'qty_ordered'            => $item['quantity'],
                        'row_total'              => $item['quantity'] * $item['price'],
                        'base_row_total'         => $item['quantity'] * $item['price'],
                        'row_invoiced'           => $item['quantity'] * $item['price'],
                        'base_row_invoiced'      => $item['quantity'] * $item['price'],
                        'tax_percent'            => $item['tax'],
                        'tax_amount'             => $item['tax'] * $item['quantity'] * $item['price'] / 100,
                        'base_tax_amount'        => $item['percent'] * $item['quantity'] * $item['price'] / 100,
                    ];

                    /** @var \Magento\Sales\Model\Order\Item $modelItems */
                    $modelItems = $modelItem->getCollection()->addFieldToFilter('order_id', $orderId)
                        ->addFieldToFilter('product_id', $productId)->getFirstItem();
                    $modelItems->addData($dataItem);
                    $modelItems->save();
                    $i++;
                }
            }
            /* save order */
            if(!empty($items->getData())) {
                $modelOrder->load($orderId,'entity_id');

                $costShip = $modelOrder->load($orderId,'entity_id')->getShippingAmount();
                $subtotal      = $subtotalOld;
                $totalDiscount = 0;
                $totalTax      = 0;
                $totalQuantity = 0;
                /** @var \Magenest\OrderManager\Model\OrderItemFactory $item */
                foreach ($items as $item) {
                    $price= $item->getPrice();
                    $quantity = $item->getQuantity();
                    $rowTotal = $quantity * $price;
                    $discount = $item->getDiscount() * $rowTotal /100;
                    $tax      = $item->getTax() * $rowTotal /100;
                    $i++;
                    $totalQuantity += $quantity;
                    $totalDiscount += $discount;
                    $totalTax += $tax;
                    $subtotal += $rowTotal;
                    $grandtotal  = $subtotal + $totalTax - $totalDiscount + $costShip;
                }

                $dataOrder = [
                    'base_grand_total'      => $grandtotal,
                    'grand_total'           => $grandtotal,
                    'base_total_invoiced'   => $grandtotal,
                    'base_total_paid'       => $grandtotal,
                    'total_invoiced'        => $grandtotal,
                    'total_due'             => $grandtotal,
                    'subtotal'              => $subtotal,
                    'base_subtotal'         => $subtotal,
                    'subtotal_invoiced'     => $subtotal,
                    'base_subtotal_invoice' => $subtotal,
                    'tax_amount'            => $totalTax,
                    'tax_invoiced'          => $totalTax,
                    'base_tax_amount'       => $totalTax,
                    'base_tax_invoiced'     => $totalTax,
                    'discount_amount'       => '-'.$totalDiscount,
                    'base_discount_amount'  => '-'.$totalDiscount,
                    'discount_invoiced'     => '-'.$totalDiscount,
                    'base_discount_invoiced'=> '-'.$totalDiscount,
                    'total_qty_ordered'     => $totalQuantity ,
                    'shipping_amount'       => $costShip,
                    'base_shipping_amount'  => $costShip,
                    'base_shipping_invoiced'=> $costShip,


                ];
                $modelOrder->addData($dataOrder);
                $modelOrder->save();

                /** @var \Magenest\OrderManager\Model\OrderItemFactory $item */
                foreach ($items as $item) {
                    $item->setData($orderId,'order_id');
                    $item->delete();
                    $totals++;
                }
            }
            $this->messageManager->addSuccess(__('Information has been submited .'));
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('sales/order/view', ['order_id' => $orderId]);
            }
            return $resultRedirect->setPath('sales/order/view', ['order_id' => $orderId]);
        } catch (\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());

        } catch (\Exception $e) {
            $this->messageManager->addError($e, __('Something went wrong while accept data'));
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            return $resultRedirect->setPath('sales/order/view', ['order_id' => $orderId]);
        }

        return $resultRedirect->setPath('sales/order/view', ['order_id' => $orderId]);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
