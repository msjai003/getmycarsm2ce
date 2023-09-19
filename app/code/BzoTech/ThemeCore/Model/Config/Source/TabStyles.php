<?php
/*------------------------------------------------------------------------
# BzoTech ThemeCore - Version 1.0.0
# Copyright (c) 2016 BzoTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: BzoTech Company
 
-------------------------------------------------------------------------*/

namespace BzoTech\ThemeCore\Model\Config\Source;

class TabStyles implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
	{
		return [
			['value' => 'default', 'label' => __('Horizontal')],
			['value' => 'vertical', 'label' => __('Vertical')],
			['value' => 'accordion', 'label' => __('Accordion')],
		];
	}
}

