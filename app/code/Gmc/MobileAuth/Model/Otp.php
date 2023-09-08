<?php

namespace Gmc\MobileAuth\Model;

use Magento\Framework\Model\AbstractModel;
use Gmc\MobileAuth\Model\ResourceModel\Otp as OtpResource;

class Otp extends AbstractModel
{
    protected $_idFieldName = 'otp_id';

    protected function _construct()
    {
        $this->_init(OtpResource::class);
    }
}
