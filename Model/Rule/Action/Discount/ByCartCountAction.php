<?php
namespace Brituy\Discount\Model\Rule\Action\Discount;

use Magento\SalesRule\Model\Validator;
use Magento\SalesRule\Model\Rule\Action\Discount\DataFactory;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Catalog\Model\ProductFactory;

class ByCartCountAction extends \Magento\SalesRule\Model\Rule\Action\Discount\AbstractDiscount
{
    const BY_CART_COUNT_ACTION = 'by_cart_count';

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

    public function getItemsToDiscount($quote, $rule)
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

        $qtyMaxProducts = (int) $rule->getDiscountQty();
        $rulePercent = min(100, $rule->getDiscountAmount());

        $itemsToDiscount = $this->getItemsToDiscount($this->cart->getQuote(), $rule);

        $idsPromoDiscount = array_keys($itemsToDiscount);

        if(in_array($item->getProduct()->getId(), $idsPromoDiscount))
        {
            $qtyToDiscount = $itemsToDiscount[$item->getProduct()->getId()];

            if ($qtyToDiscount >= 2)
            {
                if ($qtyToDiscount > $qtyMaxProducts){ $qtyToDiscount = $qtyMaxProducts; }

                $discountData->setAmount($itemPrice /100 * $rulePercent * $qtyToDiscount * $qty);
                $discountData->setBaseAmount($baseItemPrice /100 * $rulePercent * $qtyToDiscount * $qty);
                $discountData->setOriginalAmount($itemOriginalPrice /100 * $rulePercent * $qtyToDiscount * $qty);
                $discountData->setBaseOriginalAmount($baseItemOriginalPrice /100 * $rulePercent * $qtyToDiscount * $qty);
            }
        }

        return $discountData;
    }
}
