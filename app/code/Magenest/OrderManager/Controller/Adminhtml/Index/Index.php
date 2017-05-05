<?php
/**
 * Created by PhpStorm.
 */
namespace   Magenest\OrderManager\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;
    public function __construct(
        Context     $context,
        PageFactory     $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory    =   $resultPageFactory;
    }
    public function execute()
    {
        $model = $this->_objectManager->create('\Magenest\OrderManager\Model\OrderManage');
        print_r($model->getData());

        $resultPage     =   $this->resultPageFactory->create();
        return  $resultPage;
    }
    protected function _isAllowed()
    {
        return true;
    }
}
