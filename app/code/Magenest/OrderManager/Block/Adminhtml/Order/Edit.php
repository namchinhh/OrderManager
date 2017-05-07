<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderManager\Block\Adminhtml\Order;

/**
 * Class Edit
 * @package Magenest\OrderManager\Block\Adminhtml\Order
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * @var \Magento\Framework\Registry|null
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    /**
     * Edit button
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Magenest_OrderManager';
        $this->_controller = 'adminhtml_order';

        parent::_construct();

        $id = $this->getRequest()->getParam('id');
        $order = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\OrderManager\Model\OrderManage');
        $status=$order->load($id)->getStatusCheck();
        if($status=="accept"){
            $this->buttonList->remove('accept');
        }
        else{
            $this->buttonList->add(
                'accept',
                [
                    'label' => __('Accept'),
                    'onclick' => 'setLocation(\'' . $this->_getSaveAndContinueUrl() . '\')',
                    'class' => 'action-default scalable primary'
                ],
                -100
            );
        }
        $this->buttonList->add(
            'print',
            [
                'label' => __('Print Order'),
                'onclick' => 'setLocation(\'' . $this->_getPrintPdfUrl() . '\')',
                'class' => 'print'
            ],
            0
        );
        /**
         * check config
         */
        $model = \Magento\Framework\App\ObjectManager::getInstance()->create('Magento\Framework\App\Config\ScopeConfigInterface');
        $allow =  $model->getValue('ordermanager_labels/ordermanager_editor/ordermanager_delete_core', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if($allow == 0){
            $this->buttonList->remove('delete');

        }else{
            $this->buttonList->update('delete', 'label', __('Delete'));

        }
        $this->buttonList->remove('save');
        $this->buttonList->remove('reset');
    }

    /**
     * @return \Magento\Framework\Phrase
     */

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('ordermanager/*/accept', ['_current' => true]);
    }

    /**
     * @return string
     */
    protected function _getPrintPdfUrl()
    {
        return $this->getUrl('ordermanager/*/printOrder', ['_current' => true]);
    }
}
