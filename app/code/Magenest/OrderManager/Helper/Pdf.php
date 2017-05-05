<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\OrderManager\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Model\OrderFactory;

/**
 * Class Pdf
 * @package Magenest\OrderManager\Helper
 */
class Pdf extends AbstractHelper
{
    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * @var
     */
    protected $pdf;

    /**
     * @param Context $context
     * @param Pdf $pdf
     * @param OrderFactory $orderFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        OrderFactory $orderFactory,
        array $data = []
    ) {
        $this->orderFactory = $orderFactory;
        parent::__construct($context);
    }

    /**
     * @param $orderId
     * @return bool|string
     * @throws \Zend_Pdf_Exception
     */
    public function getPrintOrder($orderId)
    {
        if ($orderId) {
            $pdf   = new \Zend_Pdf();
            $page  = new \Zend_Pdf_Page('595:842');
            $model = $this->orderFactory->create()->load($orderId);
            $fontEdit = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA_BOLD);
            $number = count($model->getAllItems());

            if ($number > 0) {
                $page = $this->printPdf($page, $model);
                $y    = 762;
                $page->setFont($fontEdit, 14)
                    ->drawText(__(' ORDER '), 20, 818, 'UTF-8');
                $page->setFont($fontEdit, 13);
                $page->drawText(__('Order Number : #').$model->getIncrementId(), 50, $y, 'UTF-8');
                $page->setFont($fontEdit, 11);
                $page->drawText(__('Date : ').$model->getUpdatedAt(), 50, ($y - 15), 'UTF-8');
                $page->drawText(__('Status : ').$model->getStatus(), 50, ($y - 30), 'UTF-8');
            }
            $pdf->pages[] = $page;
            return $pdf->render();
        } else {
            return false;
        }
    }

    /**
     * @param $page
     * @param $model
     * @return mixed
     */
    public function printPdf($page, $model)
    {
        $fontRegular = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
        $fontEdit = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA_BOLD);
         $imageBackground = \Zend_Pdf_Image::imageWithPath("app/code/Magenest/OrderManager/view/frontend/web/images/Yellow.jpg");
         $page->drawImage($imageBackground, 0, 0, 595, 842);

        /** @var \Magento\Sales\Model\OrderFactory  $model*/

        $billing = $model->getBillingAddress()->getData();
        $page = $this->printAddress($page, $billing);
        if ($model->getShippingAddressId()) {
            $shipping = $model->getShippingAddress()->getData();
            $page = $this->printAddress($page, $shipping, 'ship');
        }
         $payment       = $model->getPayment()->getAdditionalInformation();
         $paymentMethod = $payment['method_title'];
         $symbol = $model->getOrderCurrency()->getCurrencySymbol();
         $shippingMethod = $model->getShippingDescription();
         $items = $model->getAllItems();

        $page->drawText($paymentMethod, 50, 475, 'UTF-8');
        $page->drawText($shippingMethod, 318, 475, 'UTF-8');
        $page->setFont($fontRegular, 11);
        $page->setFillColor(\Zend_Pdf_Color_Html::color('#000000'));

        $page->setFont($fontRegular, 9);
        /** @var \Magento\Sales\Model\Order\Item|\Magento\Sales\Model\Order\Invoice\Item $item */

        $page->setFont($fontRegular, 9);
        $page = $this->printItems($page, $items, $symbol);
        $page->setFont($fontRegular, 11);
        $page = $this->printTotal($page, $model, $symbol);

        return $page;
    }

    /**
     * Print Billing/Shipping Address
     *
     * @param \Zend_Pdf_Page $page
     * @param $data
     * @param string $type
     * @return mixed
     * @throws \Zend_Pdf_Exception
     */
    public function printAddress($page, $data, $type = 'bill')
    {
        $x = 318;
        $y = 645;
        if ($type == 'bill') {
            $x = 50;
        }
        $fontRegular = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
        $page->setFont($fontRegular, 11);
        $page->drawText($data['firstname'].' '.$data['lastname'], $x, 660, 'UTF-8');

        $street = wordwrap($data['street'], 40, "\n");

        foreach (explode('\n', $street) as $text) {
            $page->drawText(ltrim($text), $x, $y, 'UTF-8');
            $y -= 15;
        }
        $address = wordwrap($data['city'].', '.$data['region'], 40, "\n");

        foreach (explode("\n", $address) as $text) {
            $page->drawText(ltrim($text), $x, $y, 'UTF-8');
            $y -= 15;
        }

        $page->drawText($data['telephone'], $x, $y, 'UTF-8');

        return $page;
    }

    /**
     * Print all items in an order/invoice to Pdf
     *
     * @param \Zend_Pdf_Page $page
     * @param array $items
     * @param string $symbol
     * @param string $type
     * @return mixed
     * @throws \Zend_Pdf_Exception
     */
    public function printItems($page, $items, $symbol, $type = 'order')
    {
        $line = 390;
        $i = 0;
        $fontRegular = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
        $page->setFont($fontRegular, 9);
        /** @var \Magento\Sales\Model\Order\Item|\Magento\Sales\Model\Order\Invoice\Item $item */
        foreach ($items as $item) {
            $page->drawText($symbol.(float)$item->getPrice(), 342, $line, 'UTF-8');
            if ($type == 'order') {
                $qty = $item->getQtyOrdered();
            } else {
                $qty = $item->getQty();
            }
            $page->drawText(round((float)$qty, 2), 397, $line, 'UTF-8');
            $page->drawText($symbol.round($item->getBaseTaxAmount(), 2), 433, $line, 'UTF-8');
            $page->drawText($symbol.round($item->getRowTotalInclTax(), 2), 475, $line, 'UTF-8');

            $name = $item->getName();
            $name = wordwrap($name, 50, "\n");
            foreach (explode("\n", $name) as $text) {
                $page->drawText(strip_tags(ltrim($text)), 50, $line, 'UTF-8');
                $line -= 15;
            }
            $page->setFont($fontRegular, 7);
            $page->drawText(__('SKU : ').$item->getSku(), 50, $line, 'UTF-8');
            $page->setFont($fontRegular, 9);
            $line -= 15;
            $i++;
            if ($i > 9) {
                break;
            }
        }

        return $page;
    }

    /**
     * Print Total of an Invoice/Order
     *
     * @param \Zend_Pdf_Page $page
     * @param \Magento\Sales\Model\Order|\Magento\Sales\Model\Order\Invoice $model
     * @param string $symbol
     * @return mixed
     * @throws \Zend_Pdf_Exception
     */
    public function printTotal($page, $model, $symbol)
    {
        $height = 80;
        $page->drawText('DISCOUNT ', 426, $height + 15, 'UTF-8');
        $page->drawText($symbol.$model->getDiscountAmount(), 492, $height + 15, 'UTF-8');
        $page->drawText($symbol.$model->getSubtotal(), 492, $height, 'UTF-8');
        $page->drawText($symbol.$model->getBaseShippingAmount(), 492, $height - 17, 'UTF-8');
        $page->drawText($symbol.$model->getTaxAmount(), 492, $height - 36, 'UTF-8');
        $fontEdit = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA_BOLD);
        $page->setFont($fontEdit, 12);
        $page->drawText($symbol.' '.$model->getGrandTotal(), 492, $height - 54, 'UTF-8');

        return $page;
    }
}
