<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\OrderManager\Model\Config\Source;

/**
 * Class Option
 * @package Magenest\OrderManager\Model\Config\Source
 */
class Option implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [

            ['value' => '5', 'label' => __('5 days') ],
            ['value' => '10', 'label' => __('10 days')],
            ['value' => '30', 'label' => __('30 days')],
            ['value' => '100', 'label' => __('No limit')],
        ];
    }
}
