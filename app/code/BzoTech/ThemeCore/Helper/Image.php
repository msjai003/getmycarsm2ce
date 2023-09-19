<?php
/*------------------------------------------------------------------------
# BzoTech ThemeCore
# Copyright (c) 2016 BzoTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: BzoTech Company
-------------------------------------------------------------------------*/

namespace BzoTech\ThemeCore\Helper;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Helper\Image as ProductImage;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Image extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function __construct(
        private ProductImage $imageHelper,
        private ProductRepositoryInterface $productRepository
    )
    {
        //$this->imageHelper       = $imageHelper;
        //$this->productRepository = $productRepository;
    }

    /**
     * @param $productId
     * @param $imageSize
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getItemImage($productId, $imageSize)
    {
        try {
            $_product = $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            return 'product not found';
        }
        $image_url = $this->imageHelper->init($_product, $imageSize)->getUrl();
        return $image_url;
    }
}
