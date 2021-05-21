<?php
namespace Brituy\Discount\Model\Rule\Action\Discount;

use Magento\SalesRule\Model\Validator;
use Magento\SalesRule\Model\Rule\Action\Discount\DataFactory;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Catalog\Model\ProductFactory;

class x2x4x6Action extends \Magento\SalesRule\Model\Rule\Action\Discount\AbstractDiscount
{
    const X2_X4_X6_ACTION = 'x2_x4_x6';

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

            if ($qtyToDiscount > 2)
            {
                $CountQuot = intdiv($qtyToDiscount, 2);
                if (($CountQuot>=1)&&($CountQuot<2)){ $ruleX=1; }
                if (($CountQuot>=2)&&($CountQuot<3)){ $ruleX=2; }
                if ($CountQuot>=3){ $ruleX=3; }

                $discountData->setAmount($itemPrice /100 * $rulePercent * $ruleX * $qty);
                $discountData->setBaseAmount($baseItemPrice /100 * $rulePercent * $ruleX * $qty);
                $discountData->setOriginalAmount($itemOriginalPrice /100 * $rulePercent * $ruleX * $qty);
                $discountData->setBaseOriginalAmount($baseItemOriginalPrice /100 * $rulePercent * $ruleX * $qty);
            }
        }

        return $discountData;
    }
}

/**
в х2х4х6 коэффициэнт умножения скидки по факту равен целой части от деления на 2, 
соответственно $ruleX=$CountQuot
**/
