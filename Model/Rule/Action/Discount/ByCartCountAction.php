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

    public function calculate($rule, $item, $qty)
    {
        $discountData = $this->discountFactory->create();

        $itemPrice = $this->validator->getItemPrice($item);
        $baseItemPrice = $this->validator->getItemBasePrice($item);
        $itemOriginalPrice = $this->validator->getItemOriginalPrice($item);
        $baseItemOriginalPrice = $this->validator->getItemBaseOriginalPrice($item);

        $qtyMinProducts = (int) $rule->getDiscountStep();
        $discountAmount = (int) $rule->getDiscountAmount();

        //$cartQuoteItems = $this->cart->getQuote()->getAllItems();
        //$itemsToDiscount = $this->getItemsToDiscount($this->cart->getQuote(), $rule, $qtyMinProducts, $discountAmount);

        $CartItemsIds = [];

        $allQuoteItems = $this->cart->getQuote()->getAllVisibleItems();

        foreach ($this->cart->getQuote()->getAllVisibleItems() as $cartQuoteItems)
        {
            //$product = $this->productFactory->create()->load($cartQuoteItems->getProduct()->getId());
            if (array_key_exists($cartQuoteItems->getProduct()->getId(),$CartItemsIds))
            {
		        $CartItemsIds[$cartQuoteItems->getProduct()->getId()] += $cartQuoteItems->getQty();
	        } else
	        {
		        $CartItemsIds[$cartQuoteItems->getProduct()->getId()]=$cartQuoteItems->getQty();;
	        }
        }

        $idsPromoDiscount = array_keys($CartItemsIds);

        if(in_array($item->getId(), $idsPromoDiscount))
        {
            $qtyToDiscount = $itemsToDiscount[$item->getId()]['qty'];

            $amountDiscount = $itemOriginalPrice * $qtyToDiscount;

            $discountData->setAmount($amountDiscount);
            $discountData->setBaseAmount($amountDiscount);
            $discountData->setOriginalAmount(($itemOriginalPrice * $qty));
            $discountData->setBaseOriginalAmount($this->priceCurrency->round($baseItemOriginalPrice));
        }

        return $discountData;
    }
}
