<?php
/**
 * Created by PhpStorm.
 */
namespace   Magenest\OrderManager\Controller\Adminhtml\Order;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package Magenest\PDFInvoice\Controller\Adminhtml\Invoice
 */
class Index extends \Magento\Backend\App\Action
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
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage     = $this->resultPageFactory->create();

        $resultPage->setActiveMenu('Magenest_OrderManager::order');
        $resultPage->addBreadcrumb(__('Order Manager'), __('Order Manager'));
        $resultPage->addBreadcrumb(__('Manage Order Manager'), __('Manage Order Manager'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Order Manager'));

        return  $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return  true;

    }
}
