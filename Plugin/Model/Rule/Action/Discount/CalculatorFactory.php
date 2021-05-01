<?php
namespace Brituy\Discount\Plugin\Model\Rule\Action\Discount;

use Brituy\Discount\Model\Rule\Action\Discount\ByCartCountAction;

class CalculatorFactory extends \Magento\SalesRule\Model\Rule\Action\Discount\CalculatorFactory
{
    protected $classByType = [
        'by_cart_count' => 'Brituy\Discount\Model\Rule\Action\Discount\ByCartCountAction'
    ];
    
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->_objectManager = $objectManager;
    }
    
    public function aroundCreate(\Magento\SalesRule\Model\Rule\Action\Discount\CalculatorFactory $subject,callable $proceed,$type)
    {
        if ($type === ByCartCountAction::BY_CART_COUNT_ACTION)
        {
            return $this->_objectManager->create(ByCartCountAction::class);
        } else 
        {
            return $proceed($type);
	}
    }
}
