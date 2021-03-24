<?php
namespace Brituy\Discount\Model\ResourceModel\Discount;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection 
{
    protected function _construct() 
    {
	$this->_init('Brituy\Discount\Model\Discount', 'Brituy\Discount\Model\ResourceModel\Discount');
    }
}
