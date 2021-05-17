<?php
namespace Brituy\Discount\Plugin\Model\Rule\Action\Discount;

use Brituy\Discount\Model\Rule\Action\Discount\ByCartCountAction;
use Brituy\Discount\Model\Rule\Action\Discount\x2x4x6Action;
use Brituy\Discount\Model\Rule\Action\Discount\x3x5x7Action;

class CalculatorFactory extends \Magento\SalesRule\Model\Rule\Action\Discount\CalculatorFactory
{
    protected $classByType = [
        'by_cart_count' => 'Brituy\Discount\Model\Rule\Action\Discount\ByCartCountAction',
        'x2_x4_x6' => 'Brituy\Discount\Model\Rule\Action\Discount\x2x4x6Action',
        'x3_x5_x7' => 'Brituy\Discount\Model\Rule\Action\Discount\x3x5x7Action'
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
        }
        else if ($type === x2x4x6Action::X2_X4_X6_ACTION)
        {
            return $this->_objectManager->create(x2x4x6Action::class);
        }
        else if ($type === x3x5x7Action::X3_X5_X7_ACTION)
        {
            return $this->_objectManager->create(x3x5x7Action::class);
        }
        else
        {
            return $proceed($type);
	}
    }
}
