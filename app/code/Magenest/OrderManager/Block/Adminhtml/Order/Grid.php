<?php
/**
 * Created by PhpStorm.
 * User: gialam
 * Date: 25/01/2016
 * Time: 20:24
 */
namespace Magenest\OrderManager\Block\Adminhtml\Order;

/**
 * Class Grid
 * @package Magenest\OrderManager\Block\Adminhtml\Order
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magenest\OrderManager\Model\ResourceModel\OrderManage\Collection
     */
    protected $_orderCollection;

    /**
     * @var
     */
    protected $_status;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Grid constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magenest\OrderManager\Model\ResourceModel\OrderManage\Collection $orderCollection
     * @param array $data\
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magenest\OrderManager\Model\ResourceModel\OrderManage\Collection $orderCollection,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->_orderCollection = $orderCollection;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $backendHelper, $data);
        $this->setEmptyText(__('No Order change'));
    }

    /**
     * @return $this getData on order
     */
    protected function _prepareCollection()
    {
        $collection =[
            'checking',
            'accept'
        ];
        $this->setCollection($this->_orderCollection->addFieldToFilter('status_check', $collection));

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            [
                'header' =>__('ID #'),
                'index'  => 'id',
            ]
        );
        $this->addColumn(
            'order_id',
            [
                'header' =>__('Order Id'),
                'index'  => 'order_id',
            ]
        );
        $this->addColumn(
            'status',
            [
                'header' =>__('Status'),
                'index' => 'status',
            ]
        );
        $this->addColumn(
            'status_check',
            [
                'header' =>__('Censorship'),
                'index' => 'status_check',
            ]
        );
        $this->addColumn(
            'create_at',
            [
                'header' =>__('Create At'),
                'index' => 'create_at',
            ]
        );
        $this->addColumn(
            'customer_name',
            [
                'header' =>__('Customer Name'),
                'index' => 'customer_name',
            ]
        );
        $this->addColumn(
            'customer_email',
            [
                'header'  => __('Customer Email'),
                'index'   =>'customer_email',
            ]
        );

        $this->addColumn(
            'edit',
            [
                'header' => __('Action'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => '*/*/edit'
                        ],
                        'field' => 'id'
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );

        return $this;
    }

    /**
     * @return $this
     * add action in box action
     */
    protected function _prepareMassaction()
    {
        /**
         * check config
         */
        $model = \Magento\Framework\App\ObjectManager::getInstance()->create('Magento\Framework\App\Config\ScopeConfigInterface');
        $allow =  $model->getValue('ordermanager_labels/ordermanager_editor/ordermanager_delete_core', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if($allow == 0){
            return ;
        }
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setTemplate('Magento_Backend::widget/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('order');

        $this->getMassactionBlock()->addItem(
            'delete_order',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('*/*/massDelete'),
                'confirm' => __('Are you sure to delete ?')
            ]
        );

        return $this;
    }
}
