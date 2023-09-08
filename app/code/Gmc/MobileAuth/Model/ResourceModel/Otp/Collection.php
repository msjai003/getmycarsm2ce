<?php

namespace Gmc\MobileAuth\Model\ResourceModel\Otp;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Gmc\MobileAuth\Model\Otp;
use Gmc\MobileAuth\Model\ResourceModel\Otp as OtpResource;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'otp_id';

    protected function _construct()
    {
        $this->_init(Otp::class, OtpResource::class);
    }
}
