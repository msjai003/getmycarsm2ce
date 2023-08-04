<?php

namespace Gmc\Phonepe\Model\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_PHONEPE_SETTINGS = 'phonepe_settings/general/';
    const XML_PATH_PHONEPE_PRODUCTION_URL = self::XML_PATH_PHONEPE_SETTINGS . 'production_url';
    const XML_PATH_PHONEPE_UAT_URL = self::XML_PATH_PHONEPE_SETTINGS . 'uat_url';
    const XML_PATH_PHONEPE_DEBUG_MODE = self::XML_PATH_PHONEPE_SETTINGS . 'debug_mode';
    const XML_PATH_PHONEPE_SANDBOX_MODE = self::XML_PATH_PHONEPE_SETTINGS . 'sandbox_mode';
    const XML_PATH_PHONEPE_REDIRECT_URL = self::XML_PATH_PHONEPE_SETTINGS . 'redirect_url';
    const XML_PATH_PHONEPE_CALLBACK_URL = self::XML_PATH_PHONEPE_SETTINGS . 'callback_url';

    const XML_PATH_PHONEPE_SECURITY = 'phonepe_settings/security/';
    const XML_PATH_PHONEPE_PRODUCTION_SALT_KEY = self::XML_PATH_PHONEPE_SECURITY . 'production_salt_key';
    const XML_PATH_PHONEPE_UAT_SALT_KEY = self::XML_PATH_PHONEPE_SECURITY . 'uat_salt_key';
    const XML_PATH_PHONEPE_PRODUCTION_SALT_INDEX = self::XML_PATH_PHONEPE_SECURITY . 'production_salt_index';
    const XML_PATH_PHONEPE_UAT_SALT_INDEX = self::XML_PATH_PHONEPE_SECURITY . 'uat_salt_index';
    const XML_PATH_PHONEPE_PRODUCTION_MID = self::XML_PATH_PHONEPE_SECURITY . 'production_mid';
    const XML_PATH_PHONEPE_UAT_MID = self::XML_PATH_PHONEPE_SECURITY . 'uat_mid';

    public function getProductionUrl($storeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PHONEPE_PRODUCTION_URL,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function getUatUrl($storeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PHONEPE_UAT_URL,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function isDebugMode($storeCode = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_PHONEPE_DEBUG_MODE,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function isSandboxMode($storeCode = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_PHONEPE_SANDBOX_MODE,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function getProductionSaltKey($storeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PHONEPE_PRODUCTION_SALT_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function getUatSaltKey($storeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PHONEPE_UAT_SALT_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function getProductionSaltIndex($storeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PHONEPE_PRODUCTION_SALT_INDEX,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function getUatSaltIndex($storeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PHONEPE_UAT_SALT_INDEX,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function getProductionMid($storeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PHONEPE_PRODUCTION_MID,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function getUATMid($storeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PHONEPE_UAT_MID,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function getRedirectUrl($storeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PHONEPE_REDIRECT_URL,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function getCallbackUrl($storeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PHONEPE_CALLBACK_URL,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function rupeeToPaisa($rupees)
    {
        return $rupees * 100;
    }
}
