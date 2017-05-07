<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderManager\Block\Adminhtml\Order\Edit\Tab;

use Magento\Backend\Block\Widget\Form;

/**
 * ordermanager edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magenest\OrderManager\Model\OrderItemFactory
     */
    protected $_itemFactory;

    /**
     * @var \Magenest\OrderManager\Model\OrderManageFactory
     */
    protected $_orderFactory;

    /**
     * @var \Magenest\OrderManager\Model\OrderAddressFactory
     */
    protected $_addressFactory;

    /**
     * @var OrderFactory
     */
    protected $_ordercoreFactory;

    /**
     * @var \Magento\Directory\Model\RegionFactory
     */
    protected $_regionFactory;

    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    protected $_countryFactory;

    /**
     * @var \Magenest\OrderManager\Model\Connector
     */
    protected $_connector;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var string
     */
    protected $_template='order/view/history.phtml';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Main constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magenest\OrderManager\Model\OrderItemFactory $itemFactory
     * @param \Magenest\OrderManager\Model\OrderManageFactory $orderFactory
     * @param \Magenest\OrderManager\Model\Connector $connector
     * @param \Magenest\OrderManager\Helper\Total $totalInfo
     * @param \Magento\Sales\Model\OrderFactory $ordercoreFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magenest\OrderManager\Model\OrderItemFactory     $itemFactory,
        \Magenest\OrderManager\Model\OrderManageFactory   $orderFactory,
        \Magenest\OrderManager\Model\Connector            $connector,
        \Magenest\OrderManager\Helper\Total               $totalInfo,
        \Magento\Sales\Model\OrderFactory                 $ordercoreFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->_storeManager     = $storeManager;
        $this->_itemFactory      = $itemFactory;
        $this->_orderFactory     = $orderFactory;
        $this->_ordercoreFactory = $ordercoreFactory;
        $this->scopeConfig       = $scopeConfig;
        $this->_connector        = $connector;
        $this->_totalInfo        = $totalInfo;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        $id = $this->_coreRegistry->registry('id');
        $order = $this->_orderFactory->create()->getCollection()->addFieldToFilter('id', $id);
        foreach ($order as $orders) {
            $orderId = $orders->getOrderId();
            return $orderId;
        }
    }

    /**
     * getData item of order edit
     *
     * @return mixed
     */
    public function getItems()
    {
        $orderId = $this->getOrderId();
        $items   = $this->_itemFactory->create()->getCollection()->addFieldToFilter('order_id', $orderId);

        return $items;
    }

    /**
     * @return bool|string
     */
    public function getTotal()
    {
        $orderId = $this->getOrderId();
        if ($this->getItems()->getData()) {
            $data = $this->_totalInfo->getTotalData($orderId);
            return $data;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function getUpdateProductUrl()
    {
        $orderId = $this->getOrderId();

        return $this->getUrl('ordermanager/order/update', ['order_id'=>$orderId]);
    }

    /**
     * @param $itemId
     * @return string
     */
    public function getRemoveProduct($itemId)
    {
        $id = $this->getRequest()->getParam('id');

        return $this->getUrl('ordermanager/order/remove', ['id'=>$id, 'item_id'=>$itemId]);
    }

    /**
     * @return string
     */
    public function getAddProductUrl()
    {
        $orderId = $this->getOrderId();

        return $this->getUrl('ordermanager/item/grid', ['order_id'=>$orderId, 'isAjax' => true]);
    }
    /**
     * currency
     * @return string
     */
    public function getSymbolItem()
    {
        $orderId = $this->getOrderId();
        $data = $this->_ordercoreFactory->create()->load($orderId);
        $symbol = $data->getOrderCurrency()->getCurrencySymbol();

        return $symbol;
    }

    /**
     * check allow edit item by admin
     */
    public function getAllow(){
        $model = \Magento\Framework\App\ObjectManager::getInstance()->create('Magento\Framework\App\Config\ScopeConfigInterface');
        $allow =  $model->getValue('ordermanager_labels/ordermanager_editor/ordermanager_edit_item', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if($allow == 0 || $this->getAccept()==1){
            return "none";
        }
        return "block";
    }

    /**
     * check status of order
     */
    public function getAccept(){
        $id = $this->getRequest()->getParam('id');
        $order = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\OrderManager\Model\OrderManage');
        $status=$order->load($id)->getStatusCheck();
        if( $status=="accept" ){
            return 1;
        }
        return 0;
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }
    /**
     * @return mixed
     */
    public function getEnableRemove()
    {
        if($this->getAccept()==1){
            return 0;
        }
        $enable = $this->_connector->getRemoveItem();

        return $enable;
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Items Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Items Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
