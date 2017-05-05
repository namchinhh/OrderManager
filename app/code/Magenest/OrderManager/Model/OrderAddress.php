<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderManager\Model;

/**
 * Class OrderManage
 *
 * @package Magenest\OrderManager\Model
 * @method int getAddressId()
 * @method string getFirstname()
 * @method string getLastname()
 * @method string getCompany()
 * @method int getTelephone()
 * @method string getFax()
 * @method string getStreet()
 * @method string getCity()
 * @method int getRegionId()
 * @method string getPostcode()
 * @method int getCountryId()
 */

class OrderAddress extends \Magento\Framework\Model\AbstractModel
{
    public function _construct()
    {
        $this->_init('Magenest\OrderManager\Model\ResourceModel\OrderAddress');
    }
}
