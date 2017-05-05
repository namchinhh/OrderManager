<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderManager\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory  as PostCollectionFactory;
use Psr\Log\LoggerInterface;
use Magento\Backend\Model\View\Result\ForwardFactory;

/**
 * Reviews admin controller
 */
abstract class Order extends Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var PostCollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var ForwardFactory
     */
    protected $_resultForwardFactory;
    /**
     * Invoice constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param PostCollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        PostCollectionFactory $productCollectionFactory,
        LoggerInterface $loggerInterface,
        ForwardFactory $resultForwardFactory
    ) {
        $this->_logger = $loggerInterface;
        $this->_context = $context;
        $this->_coreRegistry = $coreRegistry;
        $this->_collectionFactory = $productCollectionFactory;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Magenest_OrderManager::order')
            ->addBreadcrumb(__('Manage Order Manager'), __('Manage Order Manager'));
        $resultPage->getConfig()->getTitle()->set(__('Manage Order Manager'));

        return $resultPage;
    }
    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_OrderManager::order');

    }
}
