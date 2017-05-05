<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\OrderManager\Block\Adminhtml\Order;

/**
 * Class Info
 * @package Magenest\OrderManager\Block\Adminhtml\Order
 */
class Info extends \Magento\Sales\Block\Adminhtml\Order\View\Tab\Info
{

    /**
     * Info constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Sales\Helper\Admin $adminHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Helper\Admin $adminHelper,
        array $data = []
    ) {
        parent::__construct($context, $registry, $adminHelper, $data);
    }

    /**
     * @param $orderId
     * @return string
     */
    public function getEditOrderUrl($orderId)
    {
         return $this->getUrl('ordermanager/order/edit', ['order_id' => $orderId]);
    }
}
