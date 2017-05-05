<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderManager\Controller\Order;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;

/**
 * Class Delete
 * @package Magenest\PDFInvoice\Controller\Adminhtml\Invoice
 */
class Delete extends \Magento\Framework\App\Action\Action
{
    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * Delete constructor.
     * @param Context $context
     * @param RequestInterface $request
     */
    public function __construct(
        Context $context,
        RequestInterface $request
    ) {

        $this->_request = $request;
        parent::__construct($context);
    }

    /**
     * delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $orderId = $data['order_id'];
            $modelManage = $this->_objectManager->create('Magenest\OrderManager\Model\OrderManage');
            $modelManage->load($orderId,'order_id');
            $modelManage->setData($orderId,'order_id');

            $modelItem = $this->_objectManager->create('Magenest\OrderManager\Model\OrderItem')
                ->getCollection()
                ->addFieldToFilter('order_id',$orderId);

            $modelAddress = $this->_objectManager->create('Magenest\OrderManager\Model\OrderAddress')
                ->getCollection()
                ->addFieldToFilter('order_id',$orderId);
            $totals = 0;
            $i = 0;
            try {
                $modelManage->delete();
                foreach ($modelItem as $items) {
                    $items->setData($orderId,'order_id');
                    $items->delete();
                    $totals++;
                }
                foreach ($modelAddress as $addresss) {
                    $addresss->setData($orderId,'order_id');
                    $addresss->delete();
                    $i++;
                }
                $this->messageManager->addSuccess(__('The Order has been deleted.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('ordermanager/order/history', ['_current' => true]);
                }
                return $resultRedirect->setPath('ordermanager/order/history');
            } catch (\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($orderId);
                return $resultRedirect->setPath('ordermanager/order/history');
            }
        }

        return $resultRedirect->setPath('ordermanager/order/history');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
