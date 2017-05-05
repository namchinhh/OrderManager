<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\OrderManager\Block\Product\Subtotal;

use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session as CustomerSession;

/**
 * Class Total
 * @package Magenest\OrderManager\Block\Product\Subtotal
 */
class Total extends Template
{
    /**
     * @var string
     */
    protected $_template = 'order/product/subtotal/total.phtml';

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productFactory;

    /**
     * @var \Magenest\OrderManager\Helper\Total
     */
    protected $_totalInfo;

    /**
     * Total constructor.
     * @param Template\Context $context
     * @param \Magenest\OrderManager\Helper\Total $totalInfo
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magenest\OrderManager\Helper\Total $totalInfo,
        array $data = []
    ) {
        $this->_totalInfo   = $totalInfo;
        parent::__construct($context, $data);
    }

    /**
     * getData total info
     *
     * @return bool|string
     */
    public function getTotalInfo()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        $data = $this->_totalInfo->getTotalData($orderId);

        return $data;
    }
}
