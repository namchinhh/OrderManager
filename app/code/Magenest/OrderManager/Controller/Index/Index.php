<?php
namespace Magenest\OrderManager\Controller\Index;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;


class Index extends \Magento\Framework\App\Action\Action
{
    const NONCE = '0123456789ABCDEF';
    /** @var CustomerRepositoryInterface */
    protected $_customerRepository;
    /**
     * @var \Magento\Customer\Model\Customer\Mapper
     */
    protected $_customerMapper;
    /**
     * @var CustomerInterfaceFactory
     */
    protected $_customerDataFactory;
    /**
     * @var DataObjectHelper
     */
    protected $_dataObjectHelper;
    /**
     * Default customer account page
     *
     * @return void
     */
    protected $_logger;
    protected $_productCollectionFactory;
    protected $_customerCollectionFactory;
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CustomerInterfaceFactory $customerDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Magento\Customer\Model\Customer\Mapper $customerMapper,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Session\SessionManagerInterface $session,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        \Magento\Sales\Model\Order $customerFactory
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_customerCollectionFactory = $productCollectionFactory;
        $this->_customerRepository = $customerRepository;
        $this->_customerMapper = $customerMapper;
        $this->_customerDataFactory = $customerDataFactory;
        $this->_dataObjectHelper = $dataObjectHelper;
        $this->_logger          = $logger;
        $this->_session         = $session;
        $this->_customerFactory = $customerFactory;
        $this->_directtory = $directoryList;
        parent::__construct($context);

    }//end __construct()


    public function execute()
    {
        echo "<pre>";
        echo "Demo";
        $model = $this->_objectManager->create('\Magento\Customer\Model\Customer');
        $products = $model->getCollection()->addAttributeToSelect('*')->addAttributeToFilter('name',	array(
            'like'	=>	'%lam%'
        ));
        print_r($products->getData());
        echo "</pre>";

    }//end execute()
    /**
     * Random string with lenght
     *
     * @param int $length
     * @return string
     */
    protected function getNonce($length)
    {
        $tmp = str_split(self::NONCE);
        shuffle($tmp);

        return substr(implode('', $tmp), 0, $length);
    }
}//end class
