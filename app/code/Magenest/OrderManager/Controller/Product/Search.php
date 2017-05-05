<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\OrderManager\Controller\Product;

/**
 * Class View
 * @package Magenest\OrderManager\Controller\Product
 */
class Search extends AbstractProduct
{
    /**
     * @return $this
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $this->_coreRegistry->register('name', $data['name']);
        $this->_coreRegistry->register('order_id', $data['order_id']);
        $this->_view->loadLayout();
        if (!empty($data['name'])) {
            if ($block = $this->_view->getLayout()->getBlock('ordermanager.search.product')) {
                $block->setRefererUrl($this->_redirect->getRefererUrl());
            }
            $this->_view->renderLayout();
        } else {
            return $this->resultRedirectFactory->create()
                ->setPath('ordermanager/product/add', ['order_id' => $data['order_id']]);
        }
    }
}
