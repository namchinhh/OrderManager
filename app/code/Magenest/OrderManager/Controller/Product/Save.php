<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\OrderManager\Controller\Product;

/**
 * Class Save
 * @package Magenest\OrderManager\Controller\Product
 */
class Save extends AbstractProduct
{
    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $orderId = $this->getRequest()->getParam('order_id');
        $items = json_decode($data['pTableData'], true);

        $resultRedirect = $this->resultRedirectFactory->create();
        $model = $this->_objectManager->create('Magenest\OrderManager\Model\OrderItem');

        if(isset($items)) {
            $totals = 0;
            try {
                foreach ($items as $item) {
                    $collections = $model->getCollection();
                    $productId   = $item['productId'];
                    $name        = $item['name'];
                    $sku         =  $item['sku'];
                    $price       =  $item['price'];
                    $quantity    = $item['quantity'];
                    $tax         = $item['tax'];
                    $dataInfo = [
                        'order_id'  => trim($orderId),
                        'product_id'=> trim($productId),
                        'name'      => trim($name),
                        'sku'       => trim($sku),
                        'price'     => trim($price),
                        'discount'  => '0',
                        'tax'       => $tax,
                        'quantity'  => trim($quantity),
                        'type'      => 'new',
                    ];
                    /** @var \Magenest\OrderManager\Model\OrderItem $model */
                    $model = $collections->addFieldToFilter('order_id', trim($orderId))
                                ->addFieldToFilter('product_id', trim($productId))->getFirstItem();
                    $model->addData($dataInfo);
                    $model->save();
                    $totals++;
                }
                $this->messageManager->addSuccess(__('Items has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('ordermanager/product/view', ['order_id' => $orderId]);
                }
                return $resultRedirect->setPath('ordermanager/product/view', ['order_id' => $orderId]);
            } catch (\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError($e, __('Something went wrong while saving the shipping address.'));
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }
        }

        return $resultRedirect->setPath('ordermanager/product/view',['order_id' => $orderId]);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
