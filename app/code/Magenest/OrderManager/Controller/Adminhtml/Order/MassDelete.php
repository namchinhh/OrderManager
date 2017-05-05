<?php
/**
 * Created by PhpStorm.
 * User: gialam
 * Date: 18/02/2016
 * Time: 09:05
 */
namespace Magenest\OrderManager\Controller\Adminhtml\Order;

use Magenest\OrderManager\Controller\Adminhtml\Order as AbstractOrder;

/**
 * Class MassDelete
 * @package Magenest\OrderManager\Controller\Adminhtml\Order
 */
class MassDelete extends AbstractOrder
{
    /**
     * Delete Action
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getParam('order');
        $manageOrder  = $this->_objectManager->create('Magenest\OrderManager\Model\OrderManage');
        $manageAddress  = $this->_objectManager->create('Magenest\OrderManager\Model\OrderAddress');
        $manageItem     = $this->_objectManager->create('Magenest\OrderManager\Model\OrderItem');

        if (!is_array($data) || empty($data)) {
            $this->messageManager->addError(__('Please select order(s).'));
        } else {
            $i=0;
            try {
                /* Delete Order Edit */

                foreach ($data as $dataInfo) {
                    $id            = $dataInfo;
                    $order        = $manageOrder->load($id);
                    $dataManage   =[
                        'status_check'=>'no accept',
                    ];
                    $i++;
                    $orderId = $order->getOrderId();
                    if ($orderId) {
                        $collectionItem = $manageItem->getCollection()->addFieldToFilter('order_id', $orderId);
                        $collectionAddress = $manageAddress->getCollection()->addFieldToFilter('order_id', $orderId);

                        foreach ($collectionItem as $collectionItems){
                            $infoItem = $collectionItems->getData();
                            $collectionItems->setData($infoItem);
                            $collectionItems->delete();
                            $i++;
                        }

                        foreach ($collectionAddress as $collectionAddresss) {
                            $infoAddress = $collectionAddresss->getData();
                            $collectionAddresss->setData($infoAddress);
                            $collectionAddresss->delete();
                            $i++;
                        }

                    }
                    $order->addData($dataManage);
                    $order->save();
                }

                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($data))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }

        return $this->resultRedirectFactory->create()->setPath('ordermanager/order/');
    }
}
