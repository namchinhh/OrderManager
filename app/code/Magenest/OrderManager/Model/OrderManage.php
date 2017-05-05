<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderManager\Model;

/**
 * Class OrderManage
 * @package Magenest\OrderManager\Model
 * @method int getId()
 * @method int getOrderId()
 * @method int getCustomerId()
 * @method string getStatus()
 * @method string getStatusCheck()
 * @method string getCreatAt()
 * @method string getCustomerName()
 * @method string getCustomerEmail()
 */
class OrderManage extends \Magento\Framework\Model\AbstractModel
{
    public function _construct()
    {
        $this->_init('Magenest\OrderManager\Model\ResourceModel\OrderManage');
    }
}
