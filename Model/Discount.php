<?php
namespace Brituy\Discount\Model;

use Brituy\Discount\Api\Data\DiscountInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Data\Collection\AbstractDb;

class Discount extends AbstractModel implements DiscountInterface, IdentityInterface
{
    /** Post's Statuses */
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /** CMS page cache tag */
    const CACHE_TAG = 'brituy_discount';

    /** @var string */
    protected $_cacheTag = 'brituy_discount';

    /** Prefix of model events names
     * @var string */
    protected $_eventPrefix = 'brituy_discount';

    /** @var \Magento\Framework\UrlInterface */
    protected $_urlBuilder;
    
    public function __construct(Context $context,Registry $registry,UrlInterface $urlBuilder,
    					AbstractResource $resource = null,AbstractDb $resourceCollection = null,
					array $data = [])
    {
	    $this->_urlBuilder = $urlBuilder;
	    parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }
    
    protected function _construct()
    {
        $this->_init('Brituy\Discount\Model\ResourceModel\Discount');
    }
    
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    } 
    
    public function getDiscountId()
    {
    	return $this->getData(self::DISCOUNT_ID);
    }
    
    public function getDiscountCatId()
    {
    	return $this->getData(self::DISCOUNT_CAT_ID);
    }

    public function getActivity()
    {
    	return (bool) $this->getData(self::ACTIVITY);
    }

    public function getDiscountTitle()
    {
        return $this->getData(self::DISCOUNT_TITLE);
    }

    public function getDiscountVal()
    {
        return $this->getData(self::DISCOUNT_VAL);
    }

   

    public function setDiscountId($discountid)
    {
        return $this->setData(self::DISCOUNT_ID, $discountid);
    }
    
    public function setDiscountCatId($discount_cat_id)
    {
    	return $this->setData(self::DISCOUNT_CAT_ID, $discount_cat_id);
    }
    
    public function setActivity($activity)
    {
        return $this->setData(self::ACTIVITY, $activity);
    }

    public function setDiscountTitle($discounttitle)
    {
        return $this->setData(self::DISCOUNT_TITLE, $discounttitle);
    }

    public function setDiscountVal($discountval)
    {
        return $this->setData(self::DISCOUNT_VAL, $discountval);
    }  
}
