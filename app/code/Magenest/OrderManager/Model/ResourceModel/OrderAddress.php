<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderManager\Model\ResourceModel;

class OrderAddress extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('magenest_order_manager_address', 'id');
    }
}
