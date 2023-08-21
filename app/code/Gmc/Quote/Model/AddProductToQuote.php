<?php

/**
 * Gmc_Quote
 *
 * PHP version 8.x
 *
 * @category  PHP
 * @package   Gmc\Quote
 * @author    Gmc
 * @copyright 2023 Copyright Gmc
 */

namespace Gmc\Quote\Model;

use Gmc\PartnerContribution\Model\Logger\Logger;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\DataObject\Factory as DataObjectFactory;
use Magento\Quote\Api\CartRepositoryInterface;

/**
 * Class to add product to quote
 */
class AddProductToQuote
{
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var DataObjectFactory
     */
    private $dataObjectFactory;

    /** @var CartRepositoryInterface */
    private $cartRepository;

    /**
     * @var \Magento\Quote\Model\Quote
     */
    private $quote;

    /**
     * @inheritDoc
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        ProductRepositoryInterface $productRepository,
        DataObjectFactory $dataObjectFactory,
        Logger $logger,
        CartRepositoryInterface $cartRepository
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->logger = $logger;
        $this->cartRepository = $cartRepository;
    }

    /**
     * Function to execute business logic
     *
     * @param array $postData
     * @return mixed
     */
    public function execute($postData)
    {
        $response = [
            'success' => true,
            'message' => ''
        ];
        try {
            $this->quote = $this->checkoutSession->getQuote();
            /** @var \Magento\Framework\DataObject $dataObject */
            $dataObject = $this->dataObjectFactory->create();
            $product = $this->productRepository->getById($postData['product_id']);
            $productId = $product->getId();
            $data['product'] = $productId;
            $data['qty'] = 1;
            $data['custom_price'] = $postData['price_contribution'];
            $data['contribution_percentage'] = $postData['contribution_percentage'];
            $product->addCustomOption('contribution_percentage', $postData['contribution_percentage']);
            $dataObject->addData($data);
            $this->quote->addProduct($product, $dataObject);
            $this->cartRepository->save($this->quote);
        } catch (\Throwable $exception) {
            $this->logger->critical(
                'Error occurred while adding product to quote!',
                [
                    'error' => $exception->getMessage(),
                    'class' => __CLASS__
                ]
            );
            $response = [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
        return $response;
    }
}
