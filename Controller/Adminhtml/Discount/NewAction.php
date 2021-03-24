<?php
namespace Brituy\Discount\Controller\Adminhtml\Discount;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\Forward;
use Magento\Backend\Model\View\Result\ForwardFactory;

class NewAction extends \Magento\Backend\App\Action
{
    public $resultForwardFactory;
    
    public function __construct(Context $context,PageFactory $resultPageFactory,ForwardFactory $resultForwardFactory)
    {
        $this->resultForwardFactory = $resultForwardFactory;

        parent::__construct($context);
    }
    
    /** @return \Magento\Backend\Model\View\Result\Page */
    public function execute()
    {
        $resultForward = $this->resultForwardFactory->create();
        //$resultForward->forward('edit');
	$this->_forward('edit');
        return $resultForward;
    }
    
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Brituy_Discount::discount_save');
    }
}
