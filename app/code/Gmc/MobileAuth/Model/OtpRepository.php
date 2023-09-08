<?php

namespace Gmc\MobileAuth\Model;

use Gmc\MobileAuth\Model\ResourceModel\Otp\CollectionFactory;
use Gmc\MobileAuth\Model\OtpFactory;

class OtpRepository
{
    protected $otpFactory;

    protected $otpCollectionFactory;

    public function __construct(
        CollectionFactory $otpCollectionFactory,
        OtpFactory $otpFactory
    ) {
        $this->otpCollectionFactory = $otpCollectionFactory;
        $this->otpFactory = $otpFactory;
    }

    public function saveOtp($mobile, $otp, $expirationTime)
    {
        $otpModel = $this->otpFactory->create();
        $otpModel->setData([
            'mobile' => $mobile,
            'otp_code' => $otp,
            'expires_at' => $expirationTime,
        ]);
        $otpModel->save();
    }

    public function getOtpByMobile($mobile)
    {
        $otpCollection = $this->otpCollectionFactory->create();
        $otpCollection->addFieldToFilter('mobile', $mobile);
        return $otpCollection->getFirstItem();
    }
    public function deleteOtp($otpId)
    {
        $otpModel = $this->otpFactory->create();
        $otpModel->load($otpId);
        $otpModel->delete();
    }
}
