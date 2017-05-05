<?php
/**
 * Created by PhpStorm.
 */
namespace   Magenest\OrderManager\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package Magenest\OrderManager\Controller\Index
 */
class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context     $context,
        PageFactory     $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory    =   $resultPageFactory;
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $model = $this->_objectManager->create('\Magenest\OrderManager\Model\OrderManage');
//        print_r($model->getData());

        $resultPage = $this->resultPageFactory->create();

        return  $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
