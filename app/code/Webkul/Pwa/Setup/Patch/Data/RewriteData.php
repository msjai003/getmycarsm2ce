<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Webkul\Pwa\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Patch is mechanism, that allows to do atomic upgrade data changes
 */
class RewriteData implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @var \Magento\UrlRewrite\Model\UrlRewriteFactory
     */
    protected $urlRewriteFactory;

    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory
     * @param \Magento\Store\Api\StoreRepositoryInterface $storeRepository
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->storeRepository = $storeRepository;
    }

    /**
     * Do Upgrade
     *
     * @return void
     */
    public function apply()
    {
        $storeViews = $this->storeRepository->getList();
        foreach ($storeViews as $store) {
            $urlRewriteModel = $this->urlRewriteFactory->create();
            /* set store id */
            $urlRewriteModel->setStoreId($store->getId());
            /* this url is not created by system so set as 0 */
            $urlRewriteModel->setIsSystem(0);
            /* unique identifier - set random unique value to id path */
            $urlRewriteModel->setIdPath(rand(1, 100000));
            /* set actual url path to target path field */
            $urlRewriteModel->setTargetPath("pwa/serviceworker");
            /* set requested path which you want to create */
            $urlRewriteModel->setRequestPath("service-worker.js");
            /* set current store id */
            $urlRewriteModel->save();
        }
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [

        ];
    }
}
