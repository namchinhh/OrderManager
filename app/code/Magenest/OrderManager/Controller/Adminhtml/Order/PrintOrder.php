<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.

 */
namespace Magenest\OrderManager\Controller\Adminhtml\Order;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\App\Response\Http\FileFactory;
use Magenest\OrderManager\Helper\Pdf;
use Magenest\OrderManager\Controller\Adminhtml\Order as AbstractOrder;

/**
 * Class PrintOrder
 * @package Magenest\OrderManager\Controller\Adminhtml\Order
 */
class PrintOrder extends \Magento\Backend\App\Action
{
    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var Pdforder
     */
    protected $helperPdf;

    /**
     * PdforderPrint constructor.
     * @param DateTime $dateTime
     * @param FileFactory $fileFactory
     * @param Pdforder $dataTemplate
     */
    public function _construct(
        DateTime $dateTime,
        FileFactory $fileFactory,
        Pdf $helperPdf

    ) {
        $this->fileFactory = $fileFactory;
        $this->dateTime = $dateTime;
        $this->helperPdf = $helperPdf;;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface
     * @throws \Exception
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $modelOrder = $this->_objectManager->create('Magenest\OrderManager\Model\OrderManage');
        $orderId = $modelOrder->load($id)->getOrderId();
        if ($orderId) {
            return $this->fileFactory->create(
                sprintf('order%s.pdf', $this->dateTime->date('Y-m-d_H-i-s')),
                $this->helperPdf->getPrintOrder($orderId),
                DirectoryList::VAR_DIR,
                'application/pdf'
            );
        }

        return true;
    }
}
