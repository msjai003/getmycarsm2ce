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

namespace Webkul\Pwa\Controller\Adminhtml\NotificationMessage;

use Magento\Framework\Controller\ResultFactory;

class ImageUpload extends \Webkul\Pwa\Controller\Adminhtml\NotificationMessage
{
    /**
     * Controller Action for Uploading Images.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $result = [];
        if ($this->getRequest()->isPost()) {
            try {
                $fields = $this->getRequest()->getParams();
                $pushNotificationMessageDirPath = $this->_mediaDirectory->getAbsolutePath(
                    'pwa'
                );
                $pushNotificationMessageImageDirPath = $this->_mediaDirectory->getAbsolutePath(
                    'pwa/icon'
                );
                if (!$this->_filesystemFile->fileExists($pushNotificationMessageDirPath)) {
                    $this->_filesystemFile->mkdir($pushNotificationMessageDirPath, 0777, true);
                }
                if (!$this->_filesystemFile->fileExists($pushNotificationMessageImageDirPath)) {
                    $this->_filesystemFile->mkdir($pushNotificationMessageImageDirPath, 0777, true);
                }
                $baseTmpPath = 'pwa/icon/';
                $target = $this->_mediaDirectory->getAbsolutePath($baseTmpPath);
                try {
                    $uploader = $this->_fileUploaderFactory->create(
                        ['fileId' => 'pwa_notificationmessage[icon]']
                    );
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploader->setAllowRenameFiles(true);
                    $result = $uploader->save($target);
                    if (!$result) {
                        $result = [
                            'error' => __('File can not be saved to the destination folder.'),
                            'errorcode' => ''
                        ];
                    }
                    if (isset($result['file'])) {
                        try {
                            $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
                            $result['path'] = str_replace('\\', '/', $result['path']);
                            $result['url'] = $this->storeManager
                                ->getStore()
                                ->getBaseUrl(
                                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                                ) . $this->getFilePath($baseTmpPath, $result['file']);
                            $result['name'] = $result['file'];
                            $result['id'] = $result['file'];
                        } catch (\Exception $e) {
                            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
                        }
                    }

                    $result['cookie'] = [
                        'name' => $this->_getSession()->getName(),
                        'value' => $this->_getSession()->getSessionId(),
                        'lifetime' => $this->_getSession()->getCookieLifetime(),
                        'path' => $this->_getSession()->getCookiePath(),
                        'domain' => $this->_getSession()->getCookieDomain(),
                    ];
                } catch (\Exception $e) {
                    $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
                }
            } catch (\Exception $e) {
                $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
            }
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }

    /**
     * Retrieve path
     *
     * @param string $path
     * @param string $imageName
     *
     * @return string
     */
    public function getFilePath($path, $imageName)
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }
}
