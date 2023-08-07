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

namespace Webkul\Pwa\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

class PwaHome extends \Magento\Framework\View\Element\Template
{

    /** @var \Magento\Framework\View\Helper\SecureHtmlRenderer */
    private $secureRenderer;

    /**
     * @var \Webkul\Pwa\Helper\Data
     */
    protected $_pwaHelper;

   /**
    * @param Context $context
    * @param \Webkul\Pwa\Helper\Data $pwaHelper
    * @param \Magento\Framework\App\RequestInterface $httpRequest
    * @param Filesystem $filesystem
    * @param array $data
    */
    public function __construct(
        Context $context,
        \Webkul\Pwa\Helper\Data $pwaHelper,
        \Magento\Framework\App\RequestInterface $httpRequest,
        Filesystem $filesystem,
        array $data = []
    ) {
        $this->_pwaHelper = $pwaHelper;
        $this->httpRequest = $httpRequest;
        $this->_baseDirectory = $filesystem->getDirectoryWrite(DirectoryList::PUB);
        parent::__construct($context, $data);
    }

    /**
     * ThemeColor get theme color
     *
     * @return string
     */
    public function getThemeColor()
    {
        return $this->_pwaHelper->getThemeColor();
    }

    /**
     * Get resized spash image for ios
     *
     * @param float $width
     * @param float $height
     * @return float
     */
    public function getIosSplashImage($width, $height)
    {
        return $this->_pwaHelper->resize($width, $height);
    }

    /**
     * Get resized icon for ios
     *
     * @param float $width
     * @param float $height
     * @return float
     */
    public function getIosIconImage($width, $height)
    {
        return $this->_pwaHelper->resize($width, $height, 'icon');
    }

    /**
     * Get secure url
     *
     * @return string
     */
    public function getSecureUrl()
    {
        return $this->_pwaHelper->getSecureUrl();
    }

    /**
     * Get sender id
     *
     * @return string
     */
    public function getSenderId()
    {
        return $this->_pwaHelper->getSenderId();
    }

     /**
      * Get sender key
      *
      * @return string
      */
    public function getServerKey()
    {
        return $this->_pwaHelper->getServerKey();
    }

    /**
     * Get Browser
     */
    public function getBrowser()
    {
        $u_agent = $this->httpRequest->getServer('HTTP_USER_AGENT');
        $bname = 'Unknown';
        $platform = 'Unknown';
        $ub = "Unknown";
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = ['Version', $ub, 'other'];
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';

        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
            return $matches;
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version= $matches['version'][0];
            } else {
                $version= $matches['version'][1];
            }
        } else {
            $version= $matches['version'][0];
        }

        // check if we have a number
        if ($version==null || $version=="") {
            $version="?";
        }

        return [
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        ];
    }

    /**
     * Get Helper Object
     */
    public function helperObject()
    {
        return $this->_pwaHelper;
    }

    /**
     * Get Get Path
     */

    public function getPath()
    {
        return $this->_baseDirectory->getAbsolutePath();
    }
}
