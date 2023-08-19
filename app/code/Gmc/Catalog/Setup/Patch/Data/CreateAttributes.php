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

namespace Gmc\Catalog\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Gmc\Catalog\Helper\AttributeSetup;

/**
 * CreateAttributes
 *
 * Gmc\Catalog\Setup\Patch\Data
 */
class CreateAttributes implements DataPatchInterface, PatchRevertableInterface
{
    /** @var ModuleDataSetupInterface  */
    private $moduleDataSetup;

    /** @var AttributeSetup  */
    protected $attributeSetup;

    /**
     * Constructor
     *
     * @param ModuleDataSetupInterface $moduleDataSetup ModuleDataSetupInterface
     * @param AttributeSetup           $attributeSetup  AttributeSetup
     * @throws \Exception
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        AttributeSetup $attributeSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->attributeSetup  = $attributeSetup;
    }//end __construct()

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $attributeSet = 'Default';
        $attributeGroup = 'GMC Attributes';
        try {
            // Create attribute group
            $this->attributeSetup->createAttributeGroup(
                $attributeSet,
                $attributeGroup
            );

            // Create attributes
            $attributes = [
                'estimated_selling_price' => [
                    'options' => [
                        'type' => 'decimal',
                        'input' => 'text',
                        'label' => 'Estimated Selling Price',
                        'product_listing' => true,
                        'is_used_in_grid' => true,
                        'is_filterable_in_grid' => true,
                        'required' => true
                    ],
                ],
            ];
            $this->attributeSetup->createAttributes($attributes);
            // Assign attribute set
            $attributeCodes = array_keys($attributes);
            $this->attributeSetup->assignAttributeSet(
                $attributeSet,
                $attributeGroup,
                $attributeCodes
            );
        } catch (\Exception $exception) {
            return;
        }
    }//end apply()

    /**
     * @return mixed
     */
    public function revert()
    {
        return $this;
    }//end revert()

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getAliases()
    {
        return [];
    }//end getAliases()

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public static function getDependencies()
    {
        return [];
    }//end getDependencies()
}//end class
