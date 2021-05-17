<?php
namespace Brituy\Discount\Plugin\Model\Rule\Metadata;

class ValueProvider
{
    /** Get metadata for sales rule form. It will be merged with form UI component declaration. */
    public function afterGetMetadataValues(\Magento\SalesRule\Model\Rule\Metadata\ValueProvider $subject,$result)
    {
        $applyOptions = [
            'label' => __('BrituyDiscount'),
            'value' => [
                [
                    'label' => ' Discout by Cart count',
                    'value' => 'by_cart_count'
                ],
                [
                    'label' => ' x2,x4,x6 Discout',
                    'value' => 'x2_x4_x6'
                ],
                [
                    'label' => ' x3,x5,x7 Discout',
                    'value' => 'x3_x5_x7'
                ],
            ],
        ];
        array_push($result['actions']['children']['simple_action']['arguments']['data']['config']['options'], $applyOptions);
        
        return $result;
    }
}
