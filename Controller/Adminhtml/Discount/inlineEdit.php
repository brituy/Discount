<?php
namespace Brituy\Discount\Controller\Adminhtml\Discount;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Brituy\Discount\Model\DiscountFactory;

class InlineEdit extends Action
{
    protected $jsonFactory;
    protected $_disountFactory;

    public function __construct(Context $context,JsonFactory $jsonFactory,DiscountFactory $discountFactory)
    {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->_discountFactory = $discountFactory;
        
    }

    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) 
        {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems))
            {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $discountId)
                {
                    /** load your model to update the data */
                    $model = $this->_discountFactory->create()->load($discountId);
                    try
                    {
                        $model->setData(array_merge($model->getData(), $postItems[$disountId]));
                        $model->save();
                    } catch (\Exception $e) 
                      {
                        $messages[] = "[Error:]  {$e->getMessage()}";
                        $error = true;
                      }
                }
            }
        }

        return $resultJson->setData(['messages' => $messages,'error' => $error]);
    }
    
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Brituy_Discount::discount_save');
    }
}
