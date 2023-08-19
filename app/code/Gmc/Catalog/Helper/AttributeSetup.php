<?php

/**
 * Gmc_Catalog
 *
 * PHP version 8.x
 *
 * @category  PHP
 * @package   Gmc\Catalog
 * @author    Gmc
 * @copyright 2023 Copyright Gmc
 */

declare(strict_types=1);

namespace Gmc\Catalog\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\ModuleDataSetupInterface as SetupInterface;

/**
 * Class AttributeSetup
 *
 * Gmc\Catalog\Helper
 */
class AttributeSetup extends AbstractHelper
{
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var SetupInterface
     */
    private $setupInterface;

    /**
     * @var EavSetup
     */
    private $eavSetup;

    /**
     * @var string Cache the entity type ID
     */
    private $entityTypeId;

    /**
     * AddAttributes constructor.
     * @param Context $context Context
     * @param EavSetupFactory $eavSetupFactory EavSetupFactory
     * @param SetupInterface $setupInterface SetupInterface
     * @throws LocalizedException
     */
    public function __construct(
        Context $context,
        EavSetupFactory $eavSetupFactory,
        SetupInterface $setupInterface
    ) {
        parent::__construct($context);
        $this->eavSetupFactory = $eavSetupFactory;
        $this->setupInterface = $setupInterface;
        $this->init();
    }//end __construct()

    /**
     * Create attribute group
     *
     * @param string $attributeSetName
     * @param string $attributeSetGroup
     */
    public function createAttributeGroup($attributeSetName, $attributeSetGroup)
    {
        $this->eavSetup->addAttributeGroup(
            $this->entityTypeId,
            $attributeSetName,
            $attributeSetGroup
        );
    }//end createAttributeGroup()

    /**
     * Create attributes
     *
     * @param array $attributes
     * @throws LocalizedException
     * @throws \Zend_Validate_Exception
     */
    public function createAttributes($attributes)
    {
        foreach ($attributes as $attributeCode => $attributeData) {
            $this->eavSetup->removeAttribute($this->entityTypeId, $attributeCode);
            $options = [
                'type' => $attributeData['options']['type'],
                'backend' => '',
                'frontend' => '',
                'source' => $attributeData['options']['source'] ?? null,
                'label' => $attributeData['options']['label'],
                'input' => $attributeData['options']['input'],
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => $attributeData['options']['required'] ?? false,
                'user_defined' => true,
                'searchable' => $attributeData['options']['searchable'] ?? false,
                'filterable' => $attributeData['options']['filterable'] ?? false,
                'comparable' => $attributeData['options']['comparable'] ?? false,
                'used_for_sort_by' => $attributeData['options']['used_for_sort_by'] ?? false,
                'visible_on_front' => false,
                'used_in_product_listing' => $attributeData['options']['product_listing'] ?? false,
                'is_used_in_grid' => $attributeData['options']['is_used_in_grid'] ?? false,
                'is_filterable_in_grid' => $attributeData['options']['is_filterable_in_grid'] ?? false,
                'unique' => false,
            ];
            if (isset($attributeData['options']['wysiwyg_enabled'])) {
                $options['wysiwyg_enabled'] = $attributeData['options']['wysiwyg_enabled'];
                $options['is_html_allowed_on_front'] = $attributeData['options']['is_html_allowed_on_front'] ?? false;
            }
            if (!empty($attributeData['options']['length'])) {
                $options['length'] = $attributeData['options']['length'];
            }

            $this->eavSetup->addAttribute(
                Product::ENTITY,
                $attributeCode,
                $options
            );
        }
    }//end createAttributes()

    /**
     * Assign attribute set
     *
     * @param string $attributeSetName
     * @param string $attributeSetGroup
     * @param mixed $attributeCodes
     */
    public function assignAttributeSet(
        $attributeSetName,
        $attributeSetGroup,
        $attributeCodes
    ) {
        $attributeCodes = !is_array($attributeCodes) ? [$attributeCodes] : $attributeCodes;
        foreach ($attributeCodes as $attributeCode) {
            $this->eavSetup->addAttributeToSet(
                $this->entityTypeId,
                $attributeSetName,
                $attributeSetGroup,
                $attributeCode
            );
        }
    }//end assignAttributeSet()

    /**
     * @throws LocalizedException
     */
    private function init()
    {
        /**
         * @var EavSetup
         */
        $this->eavSetup = $this->eavSetupFactory->create(['setup' => $this->setupInterface]);
        $this->entityTypeId = $this->eavSetup->getEntityTypeId(Product::ENTITY);
    }//end init()
}//end class
