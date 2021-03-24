<?php
namespace Brituy\Discount\Api\Data;


interface DiscountInterface
{
    /** Constants for keys of data array. Identical to the name of the getter in snake case */
    const DISCOUNT_ID       = 'discount_id';
    const ACTIVITY    = 'activity';
    const DISCOUNT_CAT_ID   = 'discount_cat_id';
    const DISCOUNT_TITLE     = 'discount_title';
    const DISCOUNT_VAL     = 'discount_val';

    public function getDiscountId();
    
    public function getActivity();

    public function getDiscountCatId();

    public function getDiscountTitle();
    
    public function getDiscountVal();

    

    public function setDiscountId($discountid);
    
    public function setDiscountCatId($discount_cat_id);

    public function setActivity($activity);
    
    public function setDiscountTitle($discounttitle);

    public function setDiscountVal($discountval);
}
