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
 * Class Remove
 * @package Magenest\OrderManager\Controller\Adminhtml\Order
 */
class Remove extends AbstractOrder
{
    /**
     * remove action
     * @return $this
     */
    public function execute()
    {
        $data      = $this->getRequest()->getParams();
        $id        = $data['id'];
        $itemId    = $data['item_id'];

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model     = $this->_objectManager->create('Magenest\OrderManager\Model\OrderItem');

            /** @var \Magenest\OrderManager\Model\OrderItem $modelData */
            $modelData = $model->load($itemId);

            try {
                $modelData->setData($itemId);
                $modelData->delete();
                $this->messageManager->addSuccess(__('Product have deleted .'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                return $resultRedirect->setPath('ordermanager/order/edit',['id'=>$id]);
            } catch (\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());

            } catch (\Exception $e) {
                $this->messageManager->addError($e, __('Something went wrong while remove product'));
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                return $resultRedirect->setPath('ordermanager/order/edit',['id'=>$id]);
            }
        }

        return $resultRedirect->setPath('ordermanager/order/edit',['id'=>$id]);
    }
}
