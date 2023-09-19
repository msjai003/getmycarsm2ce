<?php
/**
 * @category BZOTech
 * @package BzoTech_AjaxSearch
 * @version 1.0.0
 * @copyright Copyright (c) 2022 BZOTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * @author BZOTech Company  
 *   
 */

namespace BzoTech\AjaxSearch\Block\Search;

/**
 * Terms and conditions block
 *
 * @api
 * @since 100.0.2
 */
class Term extends \Magento\Search\Block\Term
{
    /**
     * @return $this|\Magento\Search\Block\Term
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _loadTerms()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $helper        = $objectManager->get('\BzoTech\AjaxSearch\Helper\Data');
        $termLimit     = $helper->getAjaxSearchConfig('general/term_limit');

        if (empty($this->_terms)) {
            $this->_terms = [];
            $terms        = $this->_queryCollectionFactory->create()
                ->setPopularQueryFilter($this->_storeManager->getStore()->getId())
                ->setPageSize((int)$termLimit)
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

            //natcasesort($termKeys);

            foreach ($termKeys as $termKey) {
                $this->_terms[$termKey] = $temp[$termKey];
            }
        }
        return $this;
    }
}
