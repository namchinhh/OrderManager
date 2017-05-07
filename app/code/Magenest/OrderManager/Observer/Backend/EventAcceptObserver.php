<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30/05/2016
 * Time: 16:23
 */

namespace Magenest\OrderManager\Observer\Backend;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;
use Magento\Framework\Registry;

/**
 * Class EventAcceptObserver
 * @package Magenest\OrderManager\Observer\Backend
 */
class EventAcceptObserver implements ObserverInterface
{
    const XML_PATH_EMAIL_SENDER = 'trans_email/ident_general/email';
    const XML_PATH_NAME_SENDER = 'trans_email/ident_general/name';
    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var
     */
    protected $_profileFactory;

    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;


    /**
     * EventAcceptObserver constructor.
     * @param LoggerInterface $loggerInterface
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magenest\OrderManager\Model\OrderManageFactory $manageFactory
     * @param Registry $registry
     */
    public function __construct(
        LoggerInterface $loggerInterface,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magenest\OrderManager\Model\OrderManageFactory $manageFactory,
        Registry $registry
    )
    {
        $this->_logger = $loggerInterface;
        $this->_scopeConfig = $scopeConfig;
        $this->_coreRegistry = $registry;
        $this->inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->_storeManager = $storeManager;
        $this->_manageFactory = $manageFactory;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {

        $customer_name = $observer->getCustomerName();
        $customer_email = $observer->getCustomerEmail();
        $transport = $this->_transportBuilder->setTemplateIdentifier('ordermanager_email_accept_template'
        )->setTemplateOptions(
            [
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $this->_storeManager->getStore()->getId(),
            ]
        )->setTemplateVars(
            [
                'orderId' => $observer->getOrderId()
            ]
        )->setFrom(
            [
                'email' => $this->_scopeConfig->getValue(self::XML_PATH_EMAIL_SENDER),
                'name' => $this->_scopeConfig->getValue(self::XML_PATH_NAME_SENDER),
            ]
        )->addTo(
            $customer_email,
            $customer_name
        )->getTransport();

        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }
}
