<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\OrderManager\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

/**
 * Class Order
 * @package Magenest\OrderManager\Block\Adminhtml
 */
class Order extends Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'Magenest_OrderManager';
        $this->_controller = 'adminhtml_order';
        parent::_construct();
        $this->removeButton('add');
    }
}
