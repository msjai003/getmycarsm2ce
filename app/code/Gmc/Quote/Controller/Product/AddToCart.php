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

namespace Gmc\Quote\Controller\Product;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\Http as Request;
use Magento\Framework\UrlInterface as UrlBuilder;
use Gmc\Quote\Model\AddProductToQuoteFactory;
use Gmc\Catalog\Helper\Data as Helper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;

/**
 * Class to add product to cart
 */
class AddToCart implements HttpPostActionInterface
{
    /**
     * @var UrlBuilder
     */
    private $url;

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var AddProductToQuoteFactory
     */
    private $addProductToQuoteFactory;

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var MessageManagerInterface
     */
    protected $messageManager;

    /**
     * @var Helper
     */
    private $helper;

    /**
     * Function construct
     *
     * @param Context $context
     * @param UrlBuilder $url
     * @param JsonFactory $resultJsonFactory
     * @param Request $request
     * @param AddProductToQuoteFactory $addProductToQuoteFactory
     * @param ResultFactory $resultFactory
     * @param MessageManagerInterface $messageManager
     * @param Helper $helper
     */
    public function __construct(
        Context $context,
        UrlBuilder $url,
        JsonFactory $resultJsonFactory,
        Request $request,
        AddProductToQuoteFactory $addProductToQuoteFactory,
        ResultFactory $resultFactory,
        MessageManagerInterface $messageManager,
        Helper $helper
    ) {
        $this->url = $url;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->request = $request;
        $this->addProductToQuoteFactory = $addProductToQuoteFactory;
        $this->resultFactory = $resultFactory;
        $this->messageManager = $messageManager;
        $this->helper = $helper;
    }

    /**
     * Function execute business logic
     */
    public function execute()
    {
        $postData = $this->request->getPostValue();
        $isPost = $this->request->isPost();
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        try {
            $productId = $postData['product_id'] ?? '';
            if (!$isPost || empty($productId)) {
                $resultRedirect->setPath('noroute');
                return $resultRedirect;
            }
            $cartUrl = $this->url->getUrl('checkout/cart');
            $response = [
                'success' => false,
                'redirect_url' => $cartUrl
            ];
            $contributionPercentage = (int) $postData['contribution_percentage'] ?? 0;
            if ($contributionPercentage <= 0) {
                $msg = __('Invalid Price Contribution!');
                throw new LocalizedException($msg);
            }
            $priceContributionDetails = $this->helper->getPriceContribution($productId);
            if (!$priceContributionDetails['allowed_contribution']) {
                $msg = __('Contribution Not Allowed!');
                throw new LocalizedException($msg);
            }

            $price = (int) $priceContributionDetails['price'] ?? 0;
            $priceContribution = floor(($price * ($contributionPercentage / 100)));
            if ($priceContribution <= 0) {
                $msg = __('Please adjust your contribution and try again!');
                throw new LocalizedException($msg);
            }
            $maxContributionPrice = (int) $priceContributionDetails['max_contribution_price'] ?? 0;
            if ($maxContributionPrice <= $priceContribution) {
                $msg = __('Invalid Price Contribution!');
                throw new LocalizedException($msg);
            }

            /**
             * @var $addProductToQuote \Gmc\Quote\Model\AddProductToQuote
             */
            $postData['price_contribution'] = $priceContribution;
            $addProductToQuote = $this->addProductToQuoteFactory->create();
            $result = $addProductToQuote->execute($postData);
            $response['success'] = $result['success'];
        } catch (\Exception $e) {
            $response['success'] = false;
            $msg = __($e->getMessage());
            $this->messageManager->addErrorMessage($msg);
            $response['message'] = $e->getMessage();
        }
        return $resultJson->setData($response);
    }
}
