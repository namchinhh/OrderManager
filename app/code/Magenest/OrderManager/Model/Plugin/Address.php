<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\OrderManager\Model\Plugin;

use Magento\Framework\View\Element\Template;
use Psr\Log\LoggerInterface;
use Magenest\OrderManager\Model\Connector;

/**
 * Class Address
 * @package Magenest\OrderManager\Model\Plugin
 */
class Address extends Template
{
    /**
     * @param Template\Context $context
     * @param array $data
     */
    protected $addresse;

    /**
     * @var \Magenest\OrderManager\Model\OrderAddressFactory
     */
    protected $_addressFactory;

    /**
     * @var Connector
     */
    protected $_connector;

    /**
     * Address constructor.
     * @param Template\Context $context
     * @param \Magenest\OrderManager\Model\OrderAddressFactory $addressFactory
     * @param LoggerInterface $loggerInterface
     * @param Connector $connector
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magenest\OrderManager\Model\OrderAddressFactory $addressFactory,
        LoggerInterface $loggerInterface,
        Connector $connector,
        array $data = []
    ) {
        $this->_connector = $connector;
        $this->_logger = $loggerInterface;
        $this->_addressFactory = $addressFactory;
        parent::__construct($context, $data);
    }

    /**
     * @param $subject
     * @param $addresse
     */
    public function beforeGetFormattedAddress($subject, $addresse)
    {
        $this->addresse = $addresse;
    }

    /**
     * @param $subject
     * @param $result
     * @return string
     */
    public function afterGetFormattedAddress($subject, $result)
    {
        
        $data = $subject->getOrder();
        $orderId = $data->getId();
        $type = $this->addresse->getData('address_type');

        if ($type == 'shipping') {
            if ($this->_connector->getEnableShipping() == 0) {
                if ($data->getStatus() == 'pending') {
                    $urlShipping = '<a href="' . $this->getUrl('ordermanager/address/edit', ['order_id' => $orderId, 'type' => 'shipping']) . '" class="level-top ui-corner-all">Edit</a></br>';

                    return $urlShipping . $result;
                } else {
                    return $result;
                }
            } else {
                return $result;
            }
        } else {
            if ($this->_connector->getEnableBilling() == 0) {
                if ($data->getStatus() == 'pending' || $data->getStatus() == 'complete') {
                    $urlBilling = '<a href="' . $this->getUrl('ordermanager/address/edit', ['order_id' => $orderId, 'type' => 'billing']) . '" class="level-top ui-corner-all">Edit</a></br>';

                    return $urlBilling . $result;
                } else {

                    return $result;
                }
            } else {
                return $result;
            }
        }
    }
}
