<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderManager\Model;

/**
 * Class OrderItem
 * @package Magenest\OrderManager\Model
 * @method int getId()
 * @method int getOrderId()
 * @method int getProductId()
 * @method string getThumbnail()
 * @method string getName()
 * @method string getSku()
 * @method string getPrice()
 * @method string getQuantity()
 * @method string getDiscount()
 * @method string getTax()
 * @method string getColor()
 * @method string getSize()
 * @method string getType()
 */
class OrderItem extends \Magento\Framework\Model\AbstractModel
{
    public function _construct()
    {
        $this->_init('Magenest\OrderManager\Model\ResourceModel\OrderItem');
    }
}
