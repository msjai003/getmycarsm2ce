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

namespace Webkul\Pwa\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Pwa data helper.
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $_encryptor;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $_mediaDirectory;

    /**
     * @var \Magento\Framework\Image\Factory
     */
    protected $_imageFactory;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Encryption\EncryptorInterface $encryptor
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Image\AdapterFactory $imageFactory
     * @param \Magento\Framework\Filesystem\Io\File $filesystemFile
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Magento\Framework\Filesystem\Io\File $filesystemFile
    ) {
        $this->_storeManager = $storeManager;
        $this->_encryptor = $encryptor;
        $this->_imageFactory = $imageFactory;
        $this->filesystemFile = $filesystemFile;
        parent::__construct($context);

        $this->_mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
    }

    /**
     * Is module enabled.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->scopeConfig->getValue(
            'pwa/general_settings/status',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * PWA Application name
     *
     * @return string
     */
    public function getApplicationName()
    {
        return $this->scopeConfig->getValue(
            'pwa/general_settings/application_name',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Name when PWA is minimized.
     *
     * @return string
     */
    public function getApplicationShortName()
    {
        return $this->scopeConfig->getValue(
            'pwa/general_settings/application_short_name',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Fullscreen or normal
     *
     * @return string
     */
    public function getDisplay()
    {
        return $this->scopeConfig->getValue(
            'pwa/general_settings/display',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Landscape or Portrait
     *
     * @return string
     */
    public function getOrientation()
    {
        return $this->scopeConfig->getValue(
            'pwa/general_settings/orientation',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * PWA version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->scopeConfig->getValue(
            'pwa/general_settings/version',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        )?$this->scopeConfig->getValue(
            'pwa/general_settings/version',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        ):"1.0.0";
    }

    /**
     * Is logging enabled.
     *
     * @return int
     */
    public function getCanDebug()
    {
        return (int)$this->scopeConfig->getValue(
            'pwa/general_settings/debug',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * PWA icon shown in App.
     *
     * @return string
     */
    public function getApplicationIcon()
    {
        return $this->scopeConfig->getValue(
            'pwa/general_settings/application_icon',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * PWA ICON Urls in manifest file.
     *
     * @return string
     */
    public function getApplicationIconUrl()
    {
        $icon = $this->getApplicationIcon();
        $url = $this->_storeManager->getStore()
        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)."pwa/icon/".$icon;
        return $url;
    }

    /**
     * IOS Splash screen Image.
     *
     * @return string
     */
    public function getIosSplashScreenImage()
    {
        return $this->scopeConfig->getValue(
            'pwa/general_settings/ios_splash_screen',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Resize image
     *
     * @param float $width
     * @param float $height
     * @param string $imageType
     * @return string
     */
    public function resize($width = null, $height = null, $imageType = 'iosSplash')
    {
        $image = $imageType == 'iosSplash' ? $this->getIosSplashScreenImage() :
            $this->getApplicationIcon();
        $path = "pwa/icon";
        if ($width !== null) {
            $path .= '/' . $width . 'x';
            if ($height !== null) {
                $path .= $height ;
            }
        }

        $absolutePath = $this->_mediaDirectory->getAbsolutePath("pwa/icon/") . $image;
        $imageResized = $this->_mediaDirectory->getAbsolutePath($path) . $image;

        if (!$this->filesystemFile->fileExists($imageResized)) {
            $imageFactory = $this->_imageFactory->create();
            $imageFactory->open($absolutePath);
            $imageFactory->constrainOnly(true);
            $imageFactory->keepTransparency(true);
            $imageFactory->keepFrame(true);
            $imageFactory->keepAspectRatio(true);
            $imageFactory->resize($width, $height);
            $imageFactory->save($imageResized);
        }

        return $this->_storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        ) . $path . $image;
    }

    /**
     * Splash bg color code.
     *
     * @return string
     */
    public function getSplashBgColor()
    {
        return $this->scopeConfig->getValue(
            'pwa/general_settings/splash_bg_color',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Pwa theme color code.
     *
     * @return string
     */
    public function getThemeColor()
    {
        return $this->scopeConfig->getValue(
            'pwa/general_settings/theme_color',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * FCM project sender Id.
     *
     * @return string
     */
    public function getSenderId()
    {
        return $this->_encryptor->decrypt($this->scopeConfig->getValue(
            'pwa/general_settings/application_sender_id',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        ));
    }

    /**
     * FCM server Key.
     *
     * @return string
     */
    public function getServerKey()
    {
        return $this->_encryptor->decrypt($this->scopeConfig->getValue(
            'pwa/general_settings/application_server_key',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        ));
    }

    /**
     * FCM public key.
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->_encryptor->decrypt($this->scopeConfig->getValue(
            'pwa/general_settings/application_public_key',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        ));
    }

    /**
     * GetMediaUrl get media url
     *
     * @param  string $path
     * @return
     */
    public function getMediaUrl($path = null)
    {
        if ($path) {
            return $this ->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA,
                ['_secure' => true]
            ).$path;
        } else {
            return $this ->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA,
                ['_secure' => true]
            );
        }
    }

    /**
     * Store HTTPS url.
     *
     * @return string
     */
    public function getSecureUrl()
    {
        return $this->scopeConfig->getValue(
            'web/secure/base_url',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * PWA install button position.
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->scopeConfig->getValue(
            'pwa/pwa_button_settings/button_position',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

     /**
      * PWA buton text color code
      *
      * @return string
      */
    public function getTextColor()
    {
        return $this->scopeConfig->getValue(
            'pwa/pwa_button_settings/color',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * PWA Button color code.
     *
     * @return string
     */
    public function getButtonColor()
    {
        return $this->scopeConfig->getValue(
            'pwa/pwa_button_settings/button_color',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * FCM config value.
     *
     * @param string $value
     * @return string
     */
    public function getFCMConfig($value)
    {
        return $this->scopeConfig->getValue(
            'pwa/general_settings/'.$value,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get FCM config encrypted value.
     *
     * @param string $value
     * @return string
     */
    public function getFCMConfigEncrypted($value)
    {
        return $this->_encryptor->decrypt($this->scopeConfig->getValue(
            'pwa/general_settings/'.$value,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        ));
    }

     /**
      * PWA Offline config general settings.
      *
      * @param string $value
      * @return string
      */
    public function getOfflineConfig($value)
    {
        return $this->scopeConfig->getValue(
            'pwa/general_settings/'.$value,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
