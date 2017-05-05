<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\OrderManager\Model\Plugin;

use Magento\Framework\View\Element\Template;
use Magenest\OrderManager\Model\Connector;

/**
 * Class Item
 * @package Magenest\OrderManager\Model\Plugin
 */
class Item extends Template
{
    /**
     * @param Template\Context $context
     * @param array $data
     */
    protected $alias;

    /**
     * @var
     */
    protected $useCache;

    /**
     * @var \Magenest\OrderManager\Model\OrderItemFactory
     */
    protected $_manageFactory;

    /**
     * @var Connector
     */
    protected $_connector;


    /**
     * Item constructor.
     * @param Template\Context $context
     * @param \Magenest\OrderManager\Model\OrderItemFactory $manageFactory
     * @param Connector $connector
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magenest\OrderManager\Model\OrderItemFactory $manageFactory,
        Connector $connector,
        array $data = []
    ) {
        $this->_connector = $connector;
        $this->_manageFactory = $manageFactory;
        parent::__construct($context, $data);
    }

    /**
     * @param $subject
     * @param string $alias
     * @param bool $useCache
     */
    public function beforeGetChildHtml($subject, $alias = '', $useCache = true)
    {
        $this->alias=$alias;
        $this->useCache=$useCache;
    }

    /**
     * @param $subject
     * @return string
     */
    public function afterGetChildHtml($subject)
    {
        $_order = $subject->getOrder();
        $status = $_order->getStatus();
        $layout = $subject->getLayout();
        if (!$layout) {
            return '';
        }
        $name = $subject->getNameInLayout();
        $out = '';
        if ($this->alias) {
            $childName = $layout->getChildName($name, $this->alias);
            if ($childName) {
                $out = $layout->renderElement($childName, $this->useCache);
            }
        } else {
            foreach ($layout->getChildNames($name) as $child) {
                $out .= $layout->renderElement($child, $this->useCache);
            }
        }
        $orderId = $_order->getId();
        /** @var \Magenest\OrderManager\Model\OrderItemFactory $info */
        $info = $this->_manageFactory->create()->load($orderId, 'order_id');
        if ($status == "pending") {
            if ($this->_connector->getEnableItem() == 0) {
                if ((empty($info->getData()) || ($info->getStatusCheck() == 'no accept'))) {
                    $result = '<a href="' . $this->getUrl('ordermanager/product/view', ['order_id' => $_order->getId()]) . '" class="level-top ui-corner-all">Edit</a>';
                } else {
                    $result = '';
                }
            } else {
                $result = '';
            }
        } else {
            $result = '';
        }

        return $result.$out;
    }
}
