<?php
/**
 * Created by PhpStorm.
 * User: gialam
 * Date: 6/29/2016
 * Time: 9:47 AM
 */
namespace Magenest\OrderManager\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Customer
 * @package Magenest\OrderManager\Model\Config\Source
 */
class Customer implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '1', 'label' => __('Edit Items') ],
            ['value' => '2', 'label' => __('Edit Shipping Address')],
            ['value' => '3', 'label' => __('Edit Billing Address')],
        ];
    }
}
