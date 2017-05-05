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
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
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
        $this->buttonList->add(
            'accept',
            [
                'label' => __('Accept'),
                'onclick' => 'setLocation(\'' . $this->_getSaveAndContinueUrl() . '\')',
                'class' => 'action-default scalable primary'
            ],
            -100
        );
        $this->buttonList->add(
            'print',
            [
                'label' => __('Print Order'),
                'onclick' => 'setLocation(\'' . $this->_getPrintPdfUrl() . '\')',
                'class' => 'print'
            ],
            0
        );
        $this->buttonList->update('delete', 'label', __('Delete'));
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
