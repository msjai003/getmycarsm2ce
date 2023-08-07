<?php
/**
 * Webkul Software
 *
 * @category Webkul
 * @package Webkul_Pwa
 * @author Webkul
 * @copyright Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license https://store.webkul.com/license.html
 */
namespace Webkul\Pwa\Model\System\Config\Source;

/**
 * Config source for orientation type
 *
 */
class Orientation implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return option array
     *
     * @param bool $addEmpty
     * @return array
     */
    public function toOptionArray($addEmpty = true)
    {
        $options = [];
        array_push($options, ['label' => __("Any"), 'value' => 'any']);
        array_push($options, ['label' => __("Natural"), 'value' => 'natural']);
        array_push($options, ['label' => __("Landscape"), 'value' => 'landscape']);
        array_push($options, ['label' => __("Portrait"), 'value' => 'portrait']);
        return $options;
    }
}
