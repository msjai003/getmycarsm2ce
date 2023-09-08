<?php

namespace Gmc\MobileAuth\Block;

use Magento\Framework\View\Element\Template;

class OtpVerification extends Template
{
    public function getOtpFormAction()
    {
        return $this->getUrl('mobileauth/account/verifyotp');
    }
}
