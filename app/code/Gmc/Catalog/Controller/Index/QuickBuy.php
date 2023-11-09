<?php

namespace Gmc\Catalog\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\AddressFactory;
use Magento\Checkout\Model\Cart;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\ToOrderAddress;
use Magento\Quote\Model\Quote\Item\ToOrderItem;
use Magento\Quote\Model\Quote\Address\ToOrderAddress as ToOrderAddressModel;
use Magento\Quote\Model\Quote\Payment\ToOrderPayment;
use Magento\Sales\Api\Data\OrderInterface; // Add this import
use Magento\Sales\Api\OrderRepositoryInterface; // Add this import
use Magento\Sales\Model\OrderFactory; // Add this import
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\Service\OrderService;
use Magento\Sales\Model\Order;
use Magento\Quote\Model\QuoteManagement;
use Psr\Log\LoggerInterface;

class QuickBuy extends Action
{
    protected $cart;
    protected $customerSession;
    protected $customerRepository;
    protected $addressFactory;
    protected $checkoutSession;
    protected $quoteFactory;
    protected $shippingMethodManagement;
    protected $productRepository;
    protected $orderRepository; // Add this property
    protected $orderFactory; // Add this property
    protected $toOrderAddress;
    protected $toOrderItem;
    protected $toOrderAddressModel;
    protected $toOrderPayment;
    protected $orderService; // Add this property
    protected $quoteManagement;
    protected $logger; // Add this property

    public function __construct(
        Context $context,
        Cart $cart,
        Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        AddressFactory $addressFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Quote\Api\ShippingMethodManagementInterface $shippingMethodManagement,
        ProductRepositoryInterface $productRepository,
        OrderRepositoryInterface $orderRepository, // Inject OrderRepositoryInterface
        OrderFactory $orderFactory, // Inject OrderFactory
        ToOrderAddress $toOrderAddress,
        ToOrderItem $toOrderItem,
        ToOrderAddressModel $toOrderAddressModel,
        ToOrderPayment $toOrderPayment,
        OrderService $orderService,
        QuoteManagement $quoteManagement,
        LoggerInterface $logger // Inject the logger
    ) {
        parent::__construct($context);
        $this->cart = $cart;
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->addressFactory = $addressFactory;
        $this->checkoutSession = $checkoutSession;
        $this->quoteFactory = $quoteFactory;
        $this->shippingMethodManagement = $shippingMethodManagement;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository; // Assign the injected instance
        $this->orderFactory = $orderFactory; // Assign the injected instance
        $this->toOrderAddress = $toOrderAddress;
        $this->toOrderItem = $toOrderItem;
        $this->toOrderAddressModel = $toOrderAddressModel;
        $this->toOrderPayment = $toOrderPayment;
        $this->orderService = $orderService; // Assign the injected instance
        $this->quoteManagement = $quoteManagement;
        $this->logger = $logger; // Assign the injected instance
    }

    public function execute()
    {
        $productId = $this->getRequest()->getParam('product_id');
        $product = $this->loadProduct($productId);

        $response = [];
        try {
            $this->logger->debug('Starting the order placement process...');

            if (!$product) {
                throw new LocalizedException(__('Product not found.'));
            }

            $customerId = $this->customerSession->getCustomer()->getId();
            if (!$customerId) {
                throw new LocalizedException(__('Customer is not logged in.'));
            }

            $quote = $this->checkoutSession->getQuote();
            $customer = $this->customerRepository->getById($customerId);

            $quote->setCustomer($customer);
            $quote->assignCustomer($customer);

            $params = [
                'product' => $productId,
                'qty' => 1,
                'address' => $this->getCustomerDefaultShippingAddress($customerId),
                'method' => 'freeshipping_freeshipping'
            ];

            // Set the shipping method
            $quote->getShippingAddress()->setShippingMethod('freeshipping_freeshipping');

            $this->cart->addProduct($product, $params);
            $this->cart->save();

            $quote->getPayment()->setMethod('checkmo');
            $quote->collectTotals();
            $quote->save();

            $order = $this->placeOrder($quote);

            if ($order instanceof Order) {
                $response['success'] = true;
                $response['order_id'] = $order->getIncrementId();
            } else {
                throw new LocalizedException(__('Failed to place the order.'));
            }
        } catch (LocalizedException $e) {
            $this->logger->error('LocalizedException: ' . $e->getMessage());
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        } catch (NoSuchEntityException $e) {
            $this->logger->error('NoSuchEntityException: ' . $e->getMessage());
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        } catch (\Exception $e) {
            $this->logger->error('Exception: ' . $e->getMessage());
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        $this->getResponse()->representJson(json_encode($response));
    }

    private function loadProduct($productId)
    {
        return $this->productRepository->getById($productId);
    }

    private function getCustomerDefaultShippingAddress($customerId)
    {
        $customer = $this->customerSession->getCustomer();
        $address = $this->addressFactory->create()->load($customer->getDefaultShipping());

        if ($address->getId()) {
            return $address;
        }

        return null;
    }

    private function placeOrder(Quote $quote)
    {
        try {
            $order = $this->quoteManagement->submit($quote);
            if ($order instanceof Order) {
                return $order;
            } else {
                throw new LocalizedException(__('Order creation failed.'));
            }
        } catch (\Exception $e) {
            $this->logger->error('Exception during order placement: ' . $e->getMessage());
            throw new LocalizedException(__('An error occurred during order placement: %1', $e->getMessage()));
        }
    }
}
