<?php
namespace Brituy\Discount\Controller\Adminhtml\Discount;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\Session;
use Magento\Framework\Message\Error;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Brituy\Discount\Model\DiscountFactory;

class Save extends \Magento\Backend\App\Action
{
    protected $_coreRegistry;
    protected $_resultPageFactory;
    protected $_discountFactory;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        DiscountFactory $discountFactory
    ) {
        parent::__construct($context);
        $this->_coreRegistry = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_discountFactory = $discountFactory;

    }

    /** @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity) */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        if ($data)
        {
            $discountModel = $this->_discountFactory->create();
            $discountid = $this->getRequest()->getParam('discount_id');
            if ($discountid){ $discountModel->load($discountid); }
            $discountModel->setData($data);

            try {
            		$discountModel->save();
                	$this->messageManager->addSuccess(__('Rule was successfully saved.'));

                	// Check if 'Save and Continue'
                	if ($this->getRequest()->getParam('back'))
                	{
                    		$this->_redirect('*/*/edit', ['discount_id' => $discountModel->getId(), '_current' => true]);
                    		return;
                	}
                	$this->_redirect('*/*/');
                	return;
                }
                catch (\Magento\Framework\Exception\LocalizedException $e) { $this->addSessionErrorMessages($e->getMessage()); }
                catch (\Exception $e) { $this->messageManager->addException($e, __('Something went wrong while saving the rule.')); }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', ['discount_id' => $discountid]);
        }
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Brituy_Discount::discount_save');
    }
}
