<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderManager\Controller\Product;

/**
 * Class Cancel
 * @package Magenest\OrderManager\Controller\Product
 */
class Cancel extends AbstractProduct
{
    /**
     * cancel action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $orderId = $data['order_id'];
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($orderId) {
            $modelItem = $this->_objectManager->create('Magenest\OrderManager\Model\OrderItem')
                ->getCollection()
                ->addFieldToFilter('order_id',$orderId);
            $totals = 0;
            try {

                /** @var \Magenest\OrderManager\Model\OrderItem $items */
                foreach ($modelItem as $items) {
                    $items->setData($orderId,'order_id');
                    $items->delete();
                    $totals++;
                }
                $this->messageManager->addSuccess(__('The Edit Order has been deleted.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('sales/order/view', ['order_id'=>$orderId,'_current' => true]);
                }
                return $resultRedirect->setPath('sales/order/view', ['order_id'=>$orderId]);
            } catch (\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($orderId);
                return $resultRedirect->setPath('sales/order/view', ['order_id'=>$orderId]);
            }
        }

        return $resultRedirect->setPath('sales/order/view', ['order_id'=>$orderId]);
    }
}
