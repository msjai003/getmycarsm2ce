<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Search term block
 */

namespace BzoTech\Megashop\Block\Search;

/**
 * Terms and conditions block
 *
 * @api
 * @since 100.0.2
 */
class Term extends \Magento\Search\Block\Term
{
    protected function _loadTerms()
    {
        if (empty($this->_terms)) {
            $this->_terms = [];
            $terms        = $this->_queryCollectionFactory->create()
                ->setPopularQueryFilter($this->_storeManager->getStore()->getId())
                ->setPageSize(8)
                ->load()
                ->getItems();

            if (count($terms) == 0) {
                return $this;
            }

            $this->_maxPopularity = reset($terms)->getPopularity();
            $this->_minPopularity = end($terms)->getPopularity();
            $range                = $this->_maxPopularity - $this->_minPopularity;
            $range                = $range == 0 ? 1 : $range;
            $termKeys             = [];
            foreach ($terms as $term) {
                if (!$term->getPopularity()) {
                    continue;
                }
                $term->setRatio(($term->getPopularity() - $this->_minPopularity) / $range);
                $temp[$term->getQueryText()] = $term;
                $termKeys[]                  = $term->getQueryText();
            }

            natcasesort($termKeys);

            foreach ($termKeys as $termKey) {
                $this->_terms[$termKey] = $temp[$termKey];
            }
        }
        return $this;
    }
}
