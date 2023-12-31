<?php
/*------------------------------------------------------------------------
# BzoTech ThemeCore
# Copyright (c) 2016 BzoTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: BzoTech Company
 
-------------------------------------------------------------------------*/

namespace BzoTech\ThemeCore\Controller\Index;

use \Magento\Framework\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Framework\App\Action\Action {
	/** @var  \Magento\Framework\View\Result\Page */
	protected $resultPageFactory;
	/**      * @param \Magento\Framework\App\Action\Context $context      */
	public function __construct(Context $context, PageFactory $resultPageFactory)     {
		$this->resultPageFactory = $resultPageFactory;
		parent::__construct($context);
	}

	/**
	 * Blog Index, shows a list of recent blog posts.
	 *
	 * @return \Magento\Framework\View\Result\PageFactory
	 */
	public function execute()
	{
		$resultPage = $this->resultPageFactory->create();
		$resultPage->getConfig()->getTitle()->prepend(__('BzoTech ThemeCore'));
		return $resultPage;
	}
}