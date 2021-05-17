<?php
namespace Brituy\Discount\Model\Rule\Action\Discount;

use Magento\SalesRule\Model\Validator;
use Magento\SalesRule\Model\Rule\Action\Discount\DataFactory;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Catalog\Model\ProductFactory;

class x3x5x7Action extends \Magento\SalesRule\Model\Rule\Action\Discount\AbstractDiscount
{
    const X3_X5_X7_ACTION = 'x3_x5_x7';

    protected $discountFactory;
    protected $productFactory;
    protected $priceCurrency;
    protected $cart;

    public function __construct(Validator $validator,DataFactory $discountDataFactory,PriceCurrencyInterface $priceCurrency,
    					ProductFactory $productFactory,\Magento\Checkout\Model\Cart $cart)
    {
        $this->discountFactory = $discountDataFactory;
        $this->productFactory = $productFactory;
        $this->priceCurrency = $priceCurrency;
        $this->cart = $cart;

        parent::__construct($validator, $discountDataFactory, $priceCurrency);
    }

    public function getItemsToDiscount($quote)
    {
        $CartItemsIds = [];

        foreach ($quote->getAllVisibleItems() as $cartQuoteItems)
        {
            if (array_key_exists($cartQuoteItems->getProduct()->getId(),$CartItemsIds))
            {
                $CartItemsIds[$cartQuoteItems->getProduct()->getId()] += $cartQuoteItems->getQty();
            } else
            {
                $CartItemsIds[$cartQuoteItems->getProduct()->getId()]=$cartQuoteItems->getQty();;
            }
        }

        return $CartItemsIds;
    }

    public function calculate($rule, $item, $qty)
    {
        $discountData = $this->discountFactory->create();

        $itemPrice = $this->validator->getItemPrice($item);
        $baseItemPrice = $this->validator->getItemBasePrice($item);
        $itemOriginalPrice = $this->validator->getItemOriginalPrice($item);
        $baseItemOriginalPrice = $this->validator->getItemBaseOriginalPrice($item);

        $rulePercent = min(100, $rule->getDiscountAmount());

        $cartQuote = $this->cart->getQuote(); // !!! sometime infinite loop
        $itemsToDiscount = $this->getItemsToDiscount($cartQuote);

        $idsPromoDiscount = array_keys($itemsToDiscount);

        if(in_array($item->getProduct()->getId(), $idsPromoDiscount))
        {
            $qtyToDiscount = $itemsToDiscount[$item->getProduct()->getId()];

            if ($qtyToDiscount >= 7)
            {
                $CountQuot = intdiv($qtyToDiscount, 7);
                if ($CountQuot >=1 ){ $ruleX=3; }
            }
            else if ($qtyToDiscount >= 5)
            {
                $CountQuot = intdiv($qtyToDiscount, 5);
                if ($CountQuot == 1){ $ruleX=2; }
            }
            else if ($qtyToDiscount >= 3)
            {
                $CountQuot = intdiv($qtyToDiscount, 3);
                if ($CountQuot == 1){ $ruleX=1; }
            }
            else { $ruleX=0; }

            $discountData->setAmount($itemPrice /100 * $rulePercent * $ruleX * $qty);
            $discountData->setBaseAmount($baseItemPrice /100 * $rulePercent * $ruleX * $qty);
            $discountData->setOriginalAmount($itemOriginalPrice /100 * $rulePercent * $ruleX * $qty);
            $discountData->setBaseOriginalAmount($baseItemOriginalPrice /100 * $rulePercent * $ruleX * $qty);
        }

        return $discountData;
    }
}
