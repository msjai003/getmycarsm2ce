<?php

namespace Gmc\MobileAuth\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session;

class Register extends Template
{
    protected $customerSession;

    public function __construct(
        Context $context,
        Session $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
    }

    /**
     * @return bool
     */
    public function isMobileSubmitted() : bool
    {
        return $this->customerSession->getMobileOtpMobile() !== null;
    }

    /**
     * @return bool
     */
    public function isOtpSent() : bool
    {
        return $this->customerSession->getMobileOtp() !== null;
    }
}
