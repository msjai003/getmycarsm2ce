<?php
namespace Gmc\Catalog\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class ProductDetailsAjax extends Action
{
    private $productRepository;
    private $jsonResultFactory;

    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        JsonFactory $jsonResultFactory
    ) {
        parent::__construct($context);
        $this->productRepository = $productRepository;
        $this->jsonResultFactory = $jsonResultFactory;
    }

    public function execute()
    {
        $productId = $this->getRequest()->getParam('product_id');
        $result = $this->jsonResultFactory->create();

        if ($productId) {
            try {
                $product = $this->productRepository->getById($productId);

                // Get product details
                $productData = [
                    'name' => $product->getName(),
                    'description' => $product->getDescription(),
                    'price' => $product->getPrice(),
                    'images' => $this->getProductImages($product),
                    'attributes' => $this->getProductAttributes($product),
                    // Add more product data as needed
                ];
                return $result->setData($productData);
            } catch (\Exception $e) {
                return $result->setData(['error' => $e->getMessage()]);
            }
        } else {
            return $result->setData(['error' => 'Product ID not provided']);
        }
    }

    // Helper function to get product images
    private function getProductImages($product)
    {
        $images = [];
        foreach ($product->getMediaGalleryEntries() as $entry) {
            $images[] = $entry->getFile();
        }
        return $images;
    }

    // Helper function to get product attributes
    private function getProductAttributes($product)
    {
        $attributes = [];
        foreach ($product->getAttributes() as $attribute) {
            $attributes[$attribute->getAttributeCode()] = $product->getData($attribute->getAttributeCode());
        }
        return $attributes;
    }
}
