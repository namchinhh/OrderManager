<?php
/**
 * Created by PhpStorm.
 * User: gialam
 * Date: 18/02/2016
 * Time: 09:50
 */
namespace Magenest\OrderManager\Controller\Adminhtml\Order;

use Magenest\OrderManager\Controller\Adminhtml\Order as AbstractOrder;

/**
 * Class Delete
 * @package Magenest\OrderManager\Controller\Adminhtml\Order
 */
class Delete extends AbstractOrder
{
    /**
     * @var \Magenest\OrderManager\Model\OrderManageFactory
     */
    protected $_manageFactory;

    /**
     * Delete constructor.
     * @param \Magenest\OrderManager\Model\OrderManageFactory $manageFactory
     */
    public function _construct(
        \Magenest\OrderManager\Model\OrderManageFactory $manageFactory
    ) {
        $this->_manageFactory = $manageFactory;
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $id = $data['id'];
        $model = $this->_objectManager->create('Magenest\OrderManager\Model\OrderManage')->load($id);
//        $manage = $this->_manageFactory->create()->load($id);
        $orderId = $model->getOrderId();
        $this->_coreRegistry->register('id',$id);
        $this->_eventManager->dispatch('ordermanager_send_email_after_click_button_delete');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($orderId) {
            /**
             * delete order
             */
            $modelManage = $this->_objectManager->create('Magenest\OrderManager\Model\OrderManage');
            $modelManage->load($orderId,'order_id');
            $dataManage = [
              'status_check'=>'no accept',
            ];
            $modelManage->addData($dataManage);

            /**
             * delete item(s)
             */
            $modelItem = $this->_objectManager->create('Magenest\OrderManager\Model\OrderItem')
                ->getCollection()
                ->addFieldToFilter('order_id', $orderId);

            /**
             * delete address
             */
            $modelAddress = $this->_objectManager->create('Magenest\OrderManager\Model\OrderAddress')
                ->getCollection()
                ->addFieldToFilter('order_id', $orderId);
            $totals = 0;
            $i = 0;
            try {
                $modelManage->save();

                /** @var \Magenest\OrderManager\Model\OrderItem $items */
                foreach ($modelItem as $items) {
                    $items->setData($orderId,'order_id');
                    $items->delete();
                    $totals++;
                }
            /** @var \Magenest\OrderManager\Model\OrderAddress $addresss */
                foreach ($modelAddress as $addresss) {
                    $addresss->setData($orderId,'order_id');
                    $addresss->delete();
                    $i++;
                }
                $this->messageManager->addSuccess(__('The Order has been deleted.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('ordermanager/order/', ['_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($orderId);
                return $resultRedirect->setPath('ordermanager/order/');
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
