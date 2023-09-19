<?php
/*------------------------------------------------------------------------
# BzoTech Market - Version 1.0.0
# Copyright (c) 2016 BzoTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: BzoTech Company
 
-------------------------------------------------------------------------*/

namespace BzoTech\ThemeCore\Model\Config\Source;

class ListMenuStyles implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
	{
		return [
			['value' => 'megamenu', 'label' => __('Mega Menu')],
			['value' => 'css', 'label' => __('Css Menu')],
		];
	}
}