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

namespace Webkul\Pwa\Plugin;

use Magento\Config\Model\Config\Backend\Image as Subject;

class RequireImageSystemXml
{
    /**
     * Validating icon file.
     *
     * @param Subject $subject
     * @return void
     */
    public function beforeBeforeSave(Subject $subject)
    {
        $applicableFields = ['ios_splash_screen', 'application_icon'];
        $field = $subject->getField();
        if (in_array($field, $applicableFields)) {
            $value = $subject->getValue();
            $file = $subject->getFileData();
            $errMsg = $this->getRqrdMsgByField($field);
            if (!empty($value['error']) && empty($file) &&
            (empty($value['value']) || (!empty($value['value']) && !empty($value['delete'])))
            ) {
                throw new \Magento\Framework\Exception\LocalizedException($errMsg);
            }
        }
    }

    /**
     * Icon specific message.
     *
     * @param string $field
     * @return \Magento\Framework\Phrase
     */
    public function getRqrdMsgByField($field)
    {
        switch ($field) {
            case 'ios_splash_screen':
                return __('IOS Splash Screen Image can not be empty.');
            case 'application_icon':
                return __('Application Icon can not be empty.');
        }
    }
}
