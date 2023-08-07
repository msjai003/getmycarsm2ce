<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_Pwa
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\Pwa\Block\Adminhtml\Data\Form\Element;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Math\Random;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Helper\SecureHtmlRenderer;

class Image extends \Magento\Framework\Data\Form\Element\Image
{
    public const UPLOAD_DIR = 'pwa/icon';

    /**
     * Return html code of delete checkbox element
     *
     * @return string
     */
    protected function _getDeleteCheckbox()
    {
        $html = '';
        if ($this->getValue()) {
            $label = (string)new \Magento\Framework\Phrase('Delete Image');
            $html .= '<span style="display:none" class="delete-image">';
            $html .= '<input type="checkbox"' .
                ' name="' .
                parent::getName() .
                '[delete]" value="1" class="checkbox"' .
                ' id="' .
                $this->getHtmlId() .
                '_delete"' .
                ($this->getDisabled() ? ' disabled="disabled"' : '') .
                '/>';
            $html .= '<label for="' .
                $this->getHtmlId() .
                '_delete"' .
                ($this->getDisabled() ? ' class="disabled"' : '') .
                '> ' .
                $label .
                '</label>';
            $html .= $this->_getHiddenInput();
            $html .= '</span>';
        }

        return $html;
    }

    /**
     * Get image preview url
     *
     * @return string
     */
    protected function _getUrl()
    {
        $uploadDir = empty($this->getFieldConfig()['upload_dir']['value'])
            ? self::UPLOAD_DIR : $this->getFieldConfig()['upload_dir']['value'];
        $value = $this->getValue();
        if (strpos($value, $uploadDir) !== false) {
            return $value;
        }
        $value = ltrim($value, "/\\");
        $value = $uploadDir . '/' . $value;
        return $value;
    }
}
