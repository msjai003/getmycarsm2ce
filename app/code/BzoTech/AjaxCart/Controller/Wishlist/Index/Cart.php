<?php
/**
 * @category BZOTech
 * @package BzoTech_AjaxCart
 * @version 1.0.0
 * @copyright Copyright (c) 2022 BZOTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * @author BZOTech Company 
 */
 
namespace BzoTech\AjaxCart\Controller\Wishlist\Index;

use Magento\Framework\App\Action;
use Magento\Catalog\Model\Product\Exception as ProductException;
use Magento\Framework\Controller\ResultFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Cart extends \Magento\Wishlist\Controller\AbstractIndex
{
    /**
     * @var \Magento\Wishlist\Controller\WishlistProviderInterface
     */
    protected $wishlistProvider;

    /**
     * @var \Magento\Wishlist\Model\LocaleQuantityProcessor
     */
    protected $quantityProcessor;

    /**
     * @var \Magento\Wishlist\Model\ItemFactory
     */
    protected $itemFactory;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;

    /**
     * @var \Magento\Checkout\Helper\Cart
     */
    protected $cartHelper;

    /**
     * @var \Magento\Wishlist\Model\Item\OptionFactory
     */
    private $optionFactory;

    /**
     * @var \Magento\Catalog\Helper\Product
     */
    protected $productHelper;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @var \Magento\Wishlist\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    protected $formKeyValidator;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param Action\Context $context
     * @param \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider
     * @param \Magento\Wishlist\Model\LocaleQuantityProcessor $quantityProcessor
     * @param \Magento\Wishlist\Model\ItemFactory $itemFactory
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Magento\Wishlist\Model\Item\OptionFactory $optionFactory
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Wishlist\Helper\Data $helper
     * @param \Magento\Checkout\Helper\Cart $cartHelper
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Action\Context $context,
        \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider,
        \Magento\Wishlist\Model\LocaleQuantityProcessor $quantityProcessor,
        \Magento\Wishlist\Model\ItemFactory $itemFactory,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Wishlist\Model\Item\OptionFactory $optionFactory,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Framework\Escaper $escaper,
        \Magento\Wishlist\Helper\Data $helper,
        \Magento\Checkout\Helper\Cart $cartHelper,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
    ) {
        $this->wishlistProvider = $wishlistProvider;
        $this->quantityProcessor = $quantityProcessor;
        $this->itemFactory = $itemFactory;
        $this->cart = $cart;
        $this->optionFactory = $optionFactory;
        $this->productHelper = $productHelper;
        $this->escaper = $escaper;
        $this->helper = $helper;
        $this->cartHelper = $cartHelper;
        $this->formKeyValidator = $formKeyValidator;
        $this->_objectManager = $context->getObjectManager();
        parent::__construct($context);
    }

    /**
     * Add wishlist item to shopping cart and remove from wishlist
     *
     * If Product has required options - item removed from wishlist and redirect
     * to product view page with message about needed defined required options
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $result = [];
		$params = $this->getRequest()->getParams();
		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            return $resultRedirect->setPath('*/*/');
        }

        $itemId = (int)$this->getRequest()->getParam('item');
        /* @var $item \Magento\Wishlist\Model\Item */
        $item = $this->itemFactory->create()->load($itemId);
        if (!$item->getId()) {
            $resultRedirect->setPath('*/*');
            return $resultRedirect;
        }
        $wishlist = $this->wishlistProvider->getWishlist($item->getWishlistId());
        if (!$wishlist) {
            $resultRedirect->setPath('*/*');
            return $resultRedirect;
        }

        // Set qty
        $qty = $this->getRequest()->getParam('qty');
        if (is_array($qty)) {
            if (isset($qty[$itemId])) {
                $qty = $qty[$itemId];
            } else {
                $qty = 1;
            }
        }
        $qty = $this->quantityProcessor->process($qty);
        if ($qty) {
            $item->setQty($qty);
        }

        $redirectUrl = $this->_url->getUrl('*/*');
        $configureUrl = $this->_url->getUrl(
            '*/*/configure/',
            [
                'id' => $item->getId(),
                'product_id' => $item->getProductId(),
            ]
        );
		
        try {
            /** @var \Magento\Wishlist\Model\ResourceModel\Item\Option\Collection $options */
            $options = $this->optionFactory->create()->getCollection()->addItemFilter([$itemId]);
            $item->setOptions($options->getOptionsByItem($itemId));

            $buyRequest = $this->productHelper->addParamsToBuyRequest(
                $this->getRequest()->getParams(),
                ['current_config' => $item->getBuyRequest()]
            );

            $item->mergeBuyRequest($buyRequest);
            $item->addToCart($this->cart, true);
            $this->cart->save()->getQuote()->collectTotals();
            $wishlist->save();

            if (!$this->cart->getQuote()->getHasError()) {
                $message = __(
                    'You added %1 to your shopping cart.',
                    $this->escaper->escapeHtml($item->getProduct()->getName())
                );
                $this->messageManager->addSuccess($message);
				if (isset($params['isWishlistPage'])){
					$_layout  = $this->_objectManager->get('Magento\Framework\View\LayoutInterface');
					$_layout->getUpdate()->load(['ajaxcart_wishlist_index_index']);
					$_layout->generateXml();
					$_output = $_layout->getOutput();
					$result['content'] = $_output;
					$result['isWishlistPageContent'] =  true;
				}
				$result['success'] = true;
				$result['messages'] =  $message;
				
            }

            if ($this->cartHelper->getShouldRedirectToCart()) {
                $redirectUrl = $this->cartHelper->getCartUrl();
            } else {
                $refererUrl = $this->_redirect->getRefererUrl();
                if ($refererUrl && $refererUrl != $configureUrl) {
                    $redirectUrl = $refererUrl;
                }
            }
        } catch (ProductException $e) {
            $this->messageManager->addError(__('This product(s) is out of stock.'));
			$result['success'] = false;
			$result['messages'] = __('This product(s) is out of stock.');
			
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addNotice($e->getMessage());
            $redirectUrl = $configureUrl;
			$result['url']  =  $redirectUrl;
			$result['success'] = true;
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('We can\'t add the item to the cart right now.'));
			$result['success'] = false;
			$result['messages'] = __('We can\'t add the item to the cart right now.');
			
        }

        $this->helper->calculate();
		$result['isWishlistBtn'] =   (!isset($params['isWishlistPage']) && $wishlist->getItemsCount()) ? true : false ;
		return $this->_jsonResponse($result);
       
    }
	protected function _jsonResponse($result)
    {
        return $this->getResponse()->representJson(
            $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($result)
        );
    }
}
