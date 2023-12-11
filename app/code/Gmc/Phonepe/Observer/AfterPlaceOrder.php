<?php


namespace Gmc\Phonepe\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\ResponseFactory;
use Gmc\Phonepe\Model\PhonepeApi;

class AfterPlaceOrder implements ObserverInterface
{
	public function __construct(
		private readonly ResponseFactory $_response,
		private readonly PhonepeApi $phonePe
	) {

	}

	public function execute(EventObserver $observer)
	{

		$order = $observer->getEvent()->getOrder();

        $redirectUrl = $this->phonePe->initiatePayment($order);

	    //$url = 'https://google.com/';
		$this->_response->create()
			->setRedirect($redirectUrl)
			->sendResponse();
		exit(0);
		return $this;
	}
}
