<?php

namespace Gmc\MobileAuth\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Otp extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('mobileauth_otp', 'otp_id');
    }
}
