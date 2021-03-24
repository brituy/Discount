<?php
namespace Brituy\Discount\Ui\DataProvider\Discount\Form;

use Magento\Framework\UrlInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Brituy\Discount\Model\ResourceModel\Discount\CollectionFactory;


class NewDiscountDataProvider extends AbstractDataProvider
{
    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param UrlInterface $urlBuilder
     * @param array $meta
     * @param array $data
     */
    public function __construct($name,$primaryFieldName,$requestFieldName,
        CollectionFactory $collectionFactory,UrlInterface $urlBuilder,array $meta = [],array $data = [])
    {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->collection = $collectionFactory->create();
        $this->urlBuilder = $urlBuilder;
    }

    /** {@inheritdoc} */
    public function getData()
    {
        $this->data = array_replace_recursive(
            $this->data,['config'=>['data'=>['is_active'=>1,'include_in_menu'=>1,
                        'return_session_messages_only'=>1,'use_config'=>['available_sort_by','default_sort_by']]]]);

        return $this->data;
    }
}
