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
 * Config source for display type
 *
 */
class Display implements \Magento\Framework\Option\ArrayInterface
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
        array_push($options, ['label' => __("Fullscreen"), 'value' => 'fullscreen']);
        array_push($options, ['label' => __("Standalone"), 'value' => 'standalone']);
        array_push($options, ['label' => __("Minimal UI"), 'value' => 'minimal-ui']);
        array_push($options, ['label' => __("Browser"), 'value' => 'browser']);
        return $options;
    }
}
