<?php
namespace Brituy\Discount\Plugin;

use Magento\SalesRule\Model\Rule\Action\Discount\DataFactory;
use Magento\Quote\Model\Quote\Item;

class DefaultItem
{
    protected $discountFactory;

    public function __construct(DataFactory $discountDataFactory)
    {
        $this->discountFactory = $discountDataFactory;
    }

    public function aroundGetItemData($subject, \Closure $proceed, Item $item)
    {
        $data = $proceed($item);
        $atts = [];

        $discountId = $item->getAppliedRuleIds();
        $discountAmount = $item->getDiscountAmount();

        if (($discountAmount != 0)&&($discountId != 0))
        {
            $discount = 'custom discount';

            $atts = ["discountname" => $discount];
        }else{ $atts = ["discountname" => '']; }



        return array_merge($data, $atts);
    }
}
