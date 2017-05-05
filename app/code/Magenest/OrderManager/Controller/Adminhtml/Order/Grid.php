<?php
/**
 * Created by PhpStorm.
 * User: gialam
 * Date: 20/02/2016
 * Time: 11:44
 */

namespace Magenest\OrderManager\Controller\Adminhtml\Order;

use Magenest\OrderManager\Controller\Adminhtml\Order as AbstractOrder;

/**
 * Class Grid
 * @package Magenest\OrderManager\Controller\Adminhtml\Order
 */
class Grid extends AbstractOrder
{

    /**
     * Product grid for AJAX request
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultForward = $this->_resultForwardFactory->create();

        return $resultForward->forward('index');
    }
}
