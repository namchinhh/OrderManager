<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderManager\Model\ResourceModel\OrderManage;

/**
 *  OrderManage Collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';
    /**
     *  Initialize resource collection
     *
     *  @return     void
     */
    public function _construct()
    {
        $this->_init('Magenest\OrderManager\Model\OrderManage', 'Magenest\OrderManager\Model\ResourceModel\OrderManage');
    }
}
