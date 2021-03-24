<?php
namespace Brituy\Discount\Controller\Adminhtml\Discount;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Message\Error;
use Magento\Framework\View\Result\PageFactory;
use Brituy\Discount\Model\DiscountFactory;

class Delete extends Action
{
	protected $_resultPageFactory;
	protected $_resultPage;
	protected $_discountFactory;
	
	public function __construct(Context $context, PageFactory $resultPageFactory, DiscountFactory $discountFactory)
	{
		parent::__construct($context);
		$this->_resultPageFactory = $resultPageFactory;
		$this->_discountFactory = $discountFactory;
	}
	
	public function execute()
	{
		$discountid = $this->getRequest()->getParam('discount_id');
		if($discountid>0)
		{
			$discountModel = $this->_discountFactory->create();
			$discountModel->load($discountid);
			
			try {
				$discountModel->delete();
				
				$this->messageManager->addSuccess(__('Rule successfully deleted.'));
			} catch (\Exception $e) {
				$this->messageManager->addSuccess(__('Something went wrong while deleting the rule.'));
			}
		}
		$this->_redirect('*/*');
	}
	
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Brituy_Discount::discount_delete');
	}
}
