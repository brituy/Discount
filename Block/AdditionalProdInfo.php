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
        $additionalItemInfo = [];

        $itemExtAttrib = $this->getData('item')->getExtensionAttributes()->getDiscounts();
        if($itemExtAttrib)
        {
	    foreach ($itemExtAttrib as $key => $value)
	    {
		$itemRuleAmount = $value->getDiscountData()->getAmount();
		$itemRuleID = $value->getRuleID();
		$ruleData = $this->ruleRepository->getById($itemRuleID);
		$additionalItemInfo[] = ('You got a "'.$ruleData->getName().'" discount and saved '.
                				$this->priceCurrencyInterface->format($itemRuleAmount, false, 2));
	    }
        }
        
        return $additionalItemInfo;
    }
}
