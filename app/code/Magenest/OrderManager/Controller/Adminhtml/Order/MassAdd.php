<?php
/**
 * Created by PhpStorm.
 * User: gialam
 * Date: 18/02/2016
 * Time: 09:05
 */
namespace Magenest\OrderManager\Controller\Adminhtml\Order;

use \Magento\Framework\Exception\LocalizedException;
use \Magento\Backend\App\Action\Context;

/**
 * Class MassAddItem
 * @package Magenest\OrderManager\Controller\Adminhtml\Item
 */
class MassAdd extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     */
    protected $_stockFactory;

    /**
     * @var \Magenest\OrderManager\Model\OrderManageFactory
     */
    protected $_orderFactory;

    /**
     * MassAddItem constructor.
     * @param Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productFactory
     * @param \Magenest\OrderManager\Model\OrderManageFactory $orderFactory
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockFactory
     */
    public function __construct(
        Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productFactory,
        \Magenest\OrderManager\Model\OrderManageFactory $orderFactory,
        \Magento\CatalogInventory\Api\StockStateInterface $stockFactory
    ) {
        $this->_stockFactory = $stockFactory;
        $this->_productFactory = $productFactory;
        $this->_orderFactory   = $orderFactory;
        parent::__construct($context);
    }
    /**
     * mass add item
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getParam('order');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $model = $this->_objectManager->create('Magenest\OrderManager\Model\OrderItem');
        $orderId = $this->getRequest()->getParam('order_id');
        $i = 0;
        try {
            foreach ($data as $productId)
            {
                $id = $productId;
                $collection = $this->_productFactory->create()
                    ->addAttributeToSelect('*')->addFieldToFilter('entity_id', $id);
                /** @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collections */
                foreach ($collection as $collections){
                    $dataInfo = [
                        'order_id' => $orderId,
                        'product_id' => $id,
                        'name' => $collections->getName(),
                        'sku' => $collections->getSku(),
                        'price' => $collections->getPrice(),
                        'quantity' => '1',
                        'discount' => $collections->getDiscountAmount(),
                    ];
                    $i++;
                    $modelData = $model->getCollection()->addFieldToFilter('product_id', $id)->getFirstItem();
                    $modelData->addData($dataInfo);
                    $modelData->save();
                }
            }

            $this->messageManager->addSuccess(__('Item(s) has been saved.'));
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('sales/order/view', ['order_id' => $orderId]);
            }
            return $resultRedirect->setPath('sales/order/view', ['order_id' => $orderId]);
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());

        } catch (\Exception $e) {
            $this->messageManager->addError($e, __('Something went wrong while saving the item(s).'));
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
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
