<?php
namespace Brituy\Discount\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Discount extends AbstractDb
{
    protected function _construct()
    {
    	// table name and id is Primary of Table
    	$this->_init('brituy_discount', 'discount_id');
    }
}
