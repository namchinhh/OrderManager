<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */

namespace Magenest\OrderManager\Controller\Product;

/**
 * Class AddProduct
 * @package Magenest\OrderManager\Controller\Product
 */
class Add extends AbstractProduct
{
    /**
     * return page Add new product
     */
    public function execute()
    {


        $data = $this->getRequest()->getParams();
        $this->_coreRegistry->register('order_id',$data['order_id']);

        $this->_view->loadLayout();
        if ($block = $this->_view->getLayout()->getBlock('order.add.product')) {
            $block->setRefererUrl($this->_redirect->getRefererUrl());

        }
        $this->_view->renderLayout();
    }
}
