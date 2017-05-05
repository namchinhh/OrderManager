<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */

namespace Magenest\OrderManager\Controller\Address;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Customer\Model\Session as CustomerSession;

/**
 * Class SaveBilling Address
 * @package Magenest\PDFInvoice\Controller\Adminhtml\Invoice
 */
class Save extends \Magento\Framework\App\Action\Action
{
    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Magenest\OrderManager\Model\OrderAddressFactory
     */
    protected $_addressFactory;

    /**
     * @var OrderFactory
     */
    protected $_orderFactory;

    /**
     * @var CustomerSession
     */
    protected $_customerSession;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_time;

    /**
     * Save constructor.
     * @param Context $context
     * @param RequestInterface $request
     * @param LoggerInterface $loggerInterface
     * @param \Magenest\OrderManager\Model\OrderAddressFactory $addressFactory
     * @param OrderFactory $orderFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $timeCreate
     * @param CustomerSession $customerSession
     */
    public function __construct(
        Context $context,
        RequestInterface $request,
        LoggerInterface $loggerInterface,
        \Magenest\OrderManager\Model\OrderAddressFactory $addressFactory,
        OrderFactory $orderFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $timeCreate,
        CustomerSession $customerSession

    ) {
        $this->_time = $timeCreate;
        $this->_customerSession = $customerSession;
        $this->_request = $request;
        $this->_logger = $loggerInterface;
        $this->_addressFactory = $addressFactory;
        $this->_orderFactory   = $orderFactory;
        parent::__construct($context);

    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
//        $this->_logger->addDebug(print_r($data,true));
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */

        $order = $data['order_id'];
//        $this->_logger->addDebug(print_r($order,true));
        $time = $this->_time->gmtDate();
        /**
         * get Data order on sales
         */
        $customerId = $this->_customerSession->getCustomerId();
        $orderCollection = $this->_orderFactory->create()->load($order);
        $status = $orderCollection->getStatus();
        $firstName  = $orderCollection->getCustomerFirstname();
        $lastName   = $orderCollection->getCustomerLastname();
        $email = $orderCollection->getCustomerEmail();
        $resultRedirect = $this->resultRedirectFactory->create();
        $modelOrder = $this->_objectManager->create('Magenest\OrderManager\Model\OrderManage');
        $model = $this->_objectManager->create('Magenest\OrderManager\Model\OrderAddress');

        if ($data) {

            $modelOrder->load($order, 'order_id');
            $dataOrder = [
                'order_id' => $order,
                'customer_id' =>$customerId,
                'status' => $status,
                'status_check'=>'checking',
                'customer_name' => $firstName . ' ' . $lastName,
                'customer_email' => $email,
                'create_at' =>$time,
            ];
            $modelOrder->addData($dataOrder);

                try {
                    if($data['type'] == 'billing') {
                        $billingId = $data['addressid'];
                        $model->load($billingId, 'address_id');
                        if (isset($data['default_billing'])) {
                            $collection = $this->_addressFactory->create()->getCollection()
                                ->addFieldToFilter('order_id', $order)
                                ->addFieldToFilter('address_type', 'shipping');

                            /** @var \Magenest\OrderManager\Model\OrderAddressFactory $collections */
                            foreach ($collection as $collections) {
                                $dataInfomation = [
                                    'address_id' => $billingId,
                                    'order_id' => $order,
                                    'firstname' => $collections->getFirstname(),
                                    'lastname' => $collections->getLastname(),
                                    'company' => $collections->getCompany(),
                                    'telephone' => $collections->getTelephone(),
                                    'fax' => $collections->getFax(),
                                    'street' => $collections->getStreet(),
                                    'city' => $collections->getCity(),
                                    'postcode' => $collections->getPostcode(),
                                    'region_id' => $collections->getRegionId(),
                                    'country_id' => $collections->getCountryId(),
                                    'address_type' => 'billing'
                                ];

                                $model->addData($dataInfomation);
                                $model->save();
                            }

                        } else {

                            $dataInfo = [
                                'address_id' => $billingId,
                                'order_id' => $order,
                                'firstname' => $data['firstname'],
                                'lastname' => $data['lastname'],
                                'company' => $data['company'],
                                'telephone' => $data['telephone'],
                                'fax' => $data['fax'],
                                'street' => $data['street'],
                                'city' => $data['city'],
                                'postcode' => $data['postcode'],
                                'region_id' => $data['region_id'],
                                'country_id' => $data['country_id'],
                                'address_type' => 'billing'
                            ];

                            $model->addData($dataInfo);
                            $model->save();
                        }
                    }
                    else{
                        $shippingId = $data['addressid'];
                        $model->load($shippingId,'address_id');
                        $dataInfo = [
                            'address_id'     =>$shippingId,
                            'order_id'       =>$order,
                            'firstname'      => $data['firstname'],
                            'lastname'       => $data['lastname'],
                            'company'        => $data['company'],
                            'telephone'      => $data['telephone'],
                            'fax'            => $data['fax'],
                            'street'         => $data['street'],
                            'city'           => $data['city'],
                            'postcode'       => $data['postcode'],
                            'region_id'      => $data['region_id'],
                            'country_id'     => $data['country_id'],
                            'address_type'   =>'shipping'
                        ];
                        $model->addData($dataInfo);
                        $model->save();
                    }
                    $modelOrder->save();

                    $this->messageManager->addSuccess(__('Billing Address has been sent to admin.'));
                    $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                    if ($this->getRequest()->getParam('back')) {
                        return $resultRedirect->setPath('sales/order/view',
                            ['order_id' => $this->getRequest()->getParam('order_id')]);
                    }
                    return $resultRedirect->setPath('sales/order/view',
                        ['order_id' => $this->getRequest()->getParam('order_id')]);
                } catch (\LocalizedException $e) {
                    $this->messageManager->addError($e->getMessage());

                } catch (\Exception $e) {
                    $this->messageManager->addError($e, __('Something went wrong while saving the billing address.'));
                    $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                    $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                    return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                }
            }

        return $resultRedirect->setPath('sales/order/view',['order_id' =>$this->getRequest()->getParam('order_id')]);
    }

    /**
     * @param $value
     * @param $data
     * @return string
     */

    protected function _isAllowed()
    {
        return true;
    }
}