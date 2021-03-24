<?php
namespace Brituy\Discount\Model\Source;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class CategoryList implements \Magento\Framework\Option\ArrayInterface
{
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collectionFactory
    ) {
        $this->_categoryCollectionFactory = $collectionFactory;

    }
    public function toOptionArray($addEmpty = true)
    {

        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('name');//->addRootLevelFilter()->load();
        $options = [];
        if ($addEmpty) {
            $options[] = ['label' => __('-- Please Select a Category --'), 'value' => ''];
        }
        foreach ($collection as $category) {
            $options[] = ['label' => $category->getName(), 'value' => $category->getId()];
        }
        return $options;
    }
}
