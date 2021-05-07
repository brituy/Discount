<?php
namespace Brituy\Discount\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\SalesRule\Model\RuleRepository;

class AdditionalProdInfo extends Template
{
    public function __construct(RuleRepository $ruleRepository,PriceCurrencyInterface $currencyInterface,Context $context, array $data = [])
    {
        $this->ruleRepository = $ruleRepository;
        $this->priceCurrencyInterface = $currencyInterface;
        parent::__construct($context, $data);
    }

    /** @return void */
    protected function _construct()
    {
        parent::_construct();
    }

    /** @return $this */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
    }

    /** @return additional information data */
    public function getAdditionalData()
    {
        $additionalItemInfo = '';

        $itemDiscountIds = explode(',', $this->getData('item')->getData('applied_rule_ids'));
        $itemDiscountAmount = $this->getData('item')->getData('discount_amount');
        
        if (($itemDiscountAmount != 0)||($itemDiscountIds))
        {
            $discountNames = '';
            
            foreach ($itemDiscountIds as $discountIds)
            {
                $ruleData = $this->ruleRepository->getById($discountIds);
                $discountNames .= $ruleData->getName().', ';
            }
            
            $discountNamesStr = rtrim($discountNames,', ');
            $DiscountAmount = $this->priceCurrencyInterface->format($itemDiscountAmount, false, 2);
            $additionalItemInfo = ('You got a "'.$discountNamesStr.'" discount(s) and saved '.$DiscountAmount);
        }

        return $additionalItemInfo;
    }
}
