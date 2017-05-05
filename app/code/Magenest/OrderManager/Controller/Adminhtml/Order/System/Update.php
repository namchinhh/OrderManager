<?php
/**
 * Created by PhpStorm.
 * User: gialam
 * Date: 18/02/2016
 * Time: 09:50
 */
namespace Magenest\OrderManager\Controller\Adminhtml\Order\System;

use Magento\Backend\App\Action;
use Psr\Log\LoggerInterface;
use Magenest\OrderManager\Model\OrderManageFactory;

/**
 * Class Update
 * @package Magenest\OrderManager\Controller\Adminhtml\Order
 */
class Update extends \Magento\Backend\App\Action
{
    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var OrderManageFactory
     */
    protected $_manageFactory;
    /**
     * Delete constructor.
     * @param Action\Context $context
     */
    public function __construct(
        Action\Context $context,
        LoggerInterface $loggerInterface,
        OrderManageFactory $manageFactory
    )
    {
        $this->_manageFactory = $manageFactory;
        $this->_logger = $loggerInterface;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $orderId = $data['order_id'];
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $model = $this->_objectManager->create('Magenest\OrderManager\Model\OrderItem');
        $totals = 0;

        if ($data) {
            $collection =  $model->getCollection()
                ->addFieldToFilter('order_id',$data['order_id']);
            foreach ($collection as $collections)
            {
                $dataInfo = [
                    'quantity'     => $data['quantity-'.$collections->getProductId()],
                    'discount'     => $data['discount-'.$collections->getProductId()]
                ];

                $modelSave = $model->getCollection()
                    ->addFieldToFilter('order_id',$data['order_id'])
                    ->addFieldToFilter('product_id',$collections->getProductId())
                    ->getFirstItem();
                $modelSave->addData($dataInfo);
                $modelSave->save();
                $totals++;
            }

            try {
                $this->messageManager->addSuccess(__('The Item(s) has been updated.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('sales/order/view', ['order_id' => $orderId]);

                }
                return $resultRedirect->setPath('sales/order/view', ['order_id' => $orderId]);
            } catch (\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data['order_id']);
                return $resultRedirect->setPath('sales/order/view', ['order_id' => $orderId]);
            }

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
