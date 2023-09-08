<?php

namespace Gmc\MobileAuth\Plugin\Customer;

class Session
{
    public function afterLogout(\Magento\Customer\Model\Session $subject)
    {
        $subject->unsMobileForRegistration();
        return $subject;
    }
}
