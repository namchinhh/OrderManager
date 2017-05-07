<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\OrderManager\Controller\Product;

/**
 * Class UpdateItem
 * @package Magenest\OrderManager\Controller\Product
 */
class UpdateItem extends AbstractProduct
{
    /**
     * update number of item action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $orderId = $data['order_id'];

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $model = $this->_objectManager->create('Magenest\OrderManager\Model\OrderItem');
            $collection =  $model->getCollection()->addFieldToFilter('order_id', $orderId);

            $model->load($orderId,'order_id');
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($model->getData());
            $totals = 0;
            try {
                /** @var \Magenest\OrderManager\Model\OrderItem $collections */
                foreach ($collection as $collections) {
                    $dataInfo = [
                        'quantity' => (int)$data['quantity-'.$collections->getProductId()],
                    ];

                /** @var \Magenest\OrderManager\Model\OrderItem $modelSave */
                $modelSave = $model->getCollection()->addFieldToFilter('order_id', $data['order_id'])
                        ->addFieldToFilter('product_id', $collections->getProductId())->getFirstItem();
                $modelSave->addData($dataInfo);
                $modelSave->save();
                $totals++;
                }
                $this->messageManager->addSuccess(__('The Item(s) has been updated.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('ordermanager/product/view', ['order_id'=>$orderId, '_current' => true]);

                }
                return $resultRedirect->setPath('ordermanager/product/view', ['order_id'=>$orderId]);
            } catch (\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data['order_id']);
                return $resultRedirect->setPath('ordermanager/product/view', ['order_id'=>$orderId]);
            }
        }

        return $resultRedirect->setPath('ordermanager/product/view', ['order_id'=>$orderId]);
    }
}
