<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\OrderManager\Controller\Adminhtml\Address;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;

/**
 * Class Edit
 * @package Magenest\OrderManager\Controller\Address
 */
class Edit extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var
     */
    protected $_coreRegistry;

    /**
     * Edit constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param LoggerInterface $loggerInterface
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        LoggerInterface $loggerInterface,
        \Magento\Framework\Registry $registry

    ) {
        $this->_coreRegistry        = $registry;
        $this->_logger = $loggerInterface;
        $this->_resultPageFactory = $pageFactory;

        parent::__construct($context);
    }

    /**
     * return page edit
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();

        $orderId = $data['order_id'];
        $type = $data['type'];
        $this->_coreRegistry->register('order_id', $orderId);
        $this->_coreRegistry->register('type', $type);
        $this->_view->loadLayout();

        if ($block = $this->_view->getLayout()->getBlock('ordermanager.address.edit.admin')) {
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
