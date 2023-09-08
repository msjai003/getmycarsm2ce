<?php

namespace Gmc\Sms\Model\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_SMS_SETTINGS = 'sms_settings/general/';
    const XML_PATH_SMS_BASE_URL = self::XML_PATH_SMS_SETTINGS . 'url';
    const XML_PATH_SMS_ENDPOINT = self::XML_PATH_SMS_SETTINGS . 'endpoint';
    const XML_PATH_SMS_API_KEY = self::XML_PATH_SMS_SETTINGS . 'api_key';
    const XML_PATH_SMS_SENDER_ID = self::XML_PATH_SMS_SETTINGS . 'sender_id';
    const XML_PATH_SMS_REGISTER_MSG = self::XML_PATH_SMS_SETTINGS . 'register_msg';
    const XML_PATH_SMS_OTP_MSG = self::XML_PATH_SMS_SETTINGS . 'otp_msg';

    public function getBaseUrl($storeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SMS_BASE_URL,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function getEndpoint($storeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SMS_ENDPOINT,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function getApiKey($storeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SMS_API_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function getSenderId($storeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SMS_SENDER_ID,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function getRegisterMsg($storeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SMS_REGISTER_MSG,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function getOTPMsg($storeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SMS_OTP_MSG,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }
}
