<?php
/**
 * Created by PhpStorm.
 * User: gialam
 * Date: 17/02/2016
 * Time: 14:12
 */
namespace Magenest\OrderManager\Controller\Adminhtml\Order;

use Magenest\OrderManager\Controller\Adminhtml\Order as AbstractOrder;

/**
 * Class Edit
 * @package Magenest\OrderManager\Controller\Adminhtml\Order
 */
class Edit extends AbstractOrder
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('Magenest\OrderManager\Model\OrderAddress');

//        // 3. Set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
//
//        // 4. Register model to use later in blocks
        $this->_coreRegistry->register('id', $id);

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->getConfig()->getTitle()
            ->prepend(__('Information'));

        return $resultPage;
    }
}
