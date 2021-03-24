<?php
namespace Brituy\Discount\Controller\Adminhtml\Discount;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Brituy\Discount\Model\DiscountFactory;

class Edit extends \Magento\Backend\App\Action
{
    private $coreRegistry;

    public $resultPageFactory;

    protected $resultForwardFactory;
    protected $_discountFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry,
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Registry $coreRegistry,
        DiscountFactory $discountFactory
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->_discountFactory = $discountFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $discountid = (int) $this->getRequest()->getParam('discount_id');
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        if ($discountid) {
            $discountData = $this->_discountFactory->create()->load($discountid);

            if (!$discountData->getId()) {
                $this->messageManager->addError(__('Rule no longer exist.'));
                $this->_redirect('*/*/*');
                return;
            }
        }else{ $discountData = $this->_discountFactory->create(); }

        $this->coreRegistry->register('discount_rule', $discountData);
        $resultPageFactory = $this->resultPageFactory->create();
        $resultPageFactory->getConfig()->getTitle()->prepend(
            $discountData->getId()
                ? __('Edit Rule [%1]', $discountData->getId())
                : __('Create New Rule')
        );
        return $resultPageFactory;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Brituy_Discount::discount_save');
    }
}
