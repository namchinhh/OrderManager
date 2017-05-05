<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\OrderManager\Model\Plugin\Admin;

/**
 * Class Item
 * @package Magenest\OrderManager\Model\Plugin\Admin
 */
class Item extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magenest\OrderManager\Model\OrderManageFactory
     */
    protected $_manageFactory;

    /**
     * @var \Magenest\OrderManager\Model\Connector
     */
    protected $_connector;

    /**
     * Item constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magenest\OrderManager\Model\OrderManageFactory $manageFactory
     * @param \Magenest\OrderManager\Model\Connector $connector
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magenest\OrderManager\Model\OrderManageFactory $manageFactory,
        \Magenest\OrderManager\Model\Connector $connector,
        array $data = []
    ) {
        $this->_connector = $connector;
        $this->_manageFactory = $manageFactory;
        parent::__construct($context, $data);
    }

    /**
     * @param $subject
     * @return string
     */
    public function afterGetItemsHtml($subject)
    {
        $_order = $subject->getOrder();
        $enable = $this->_connector->getEditItem();
        $model = $this->_manageFactory->create()->load($_order->getId(), 'order_id');
        if ($enable == 1) {
            if (empty($model->getData())) {
                $grid = $this->getLayout()->createBlock('Magenest\OrderManager\Block\Adminhtml\Order\System\Edit\Item');
                $new = $this->getLayout()->createBlock('Magenest\OrderManager\Block\Adminhtml\Order\System\NewItem');
                return "<button id='add' class='action-secondary action-add' style='float: right;' type='button'>
                <span>Add Products</span>
                </button><div id='edit_item' class='no-display' >" . $grid->toHtml() . '</div>' .
                '<div>' . $new->toHtml() . '</div>' .
                "<script type='text/javascript'>" .
                "require([
            'jquery'
         ], function($) {" .
                "'use strict';" .
                "$('#add').click(function(){" .
                "$('#edit_item').show();" .
                "$('#add').hide();" .
                "})  });</script>" . $subject->getChildHtml('order_items');
            } else {
                return $subject->getChildHtml('order_items');
            }
        } else {
            return $subject->getChildHtml('order_items');
        }
    }
}
