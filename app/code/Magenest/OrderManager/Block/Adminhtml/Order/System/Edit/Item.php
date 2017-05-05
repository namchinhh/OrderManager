<?php
/**
 * Created by PhpStorm.
 * User: gialam
 * Date: 25/01/2016
 * Time: 20:24
 */
namespace Magenest\OrderManager\Block\Adminhtml\Order\System\Edit;

/**
 * Class Item
 * @package Magenest\OrderManager\Block\Adminhtml\Order\System\Edit
 */
class Item extends \Magenest\OrderManager\Block\Adminhtml\Item\Grid
{
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setTemplate('Magento_Backend::widget/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('order');
        $orderId = $this->getRequest()->getParam('order_id');
        $this->getMassactionBlock()->addItem(
            'add_item',
            [
                'label' => __('Add Item(s)'),
                'url' => $this->getUrl('ordermanager/order/massAdd', ['order_id'=>$orderId]),
                'confirm' => __('Are you sure to add product(s) ?')
            ]
        );

        return $this;
    }
}
