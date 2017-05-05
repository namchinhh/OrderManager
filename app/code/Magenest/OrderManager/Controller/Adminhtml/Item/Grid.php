<?php
/**
 * Created by PhpStorm.
 * User: gialam
 * Date: 17/02/2016
 * Time: 14:12
 */
namespace Magenest\OrderManager\Controller\Adminhtml\Item;

use Magento\Backend\App\Action;

/**
 * Class Grid
 * @package Magenest\OrderManager\Controller\Adminhtml\Item
 */
class Grid extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry|null
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory    = $resultPageFactory;
        $this->_coreRegistry        = $registry;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */


    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();

        return $resultPage;
    }

    /**
     * @return $this|\Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        // 1. Get ID and return NewPage
        $orderId = $this->getRequest()->getParam('order_id');
        $this->_view->loadLayout();
        if ($block = $this->_view->getLayout()->getBlock('order.item.collection.grid')) {
            $block->setRefererUrl($this->_redirect->getRefererUrl());
        }
        $this->_view->renderLayout();

    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
