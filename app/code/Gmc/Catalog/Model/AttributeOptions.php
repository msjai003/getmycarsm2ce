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

namespace Gmc\Catalog\Model;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface as ProductAttributeRepository;

/**
 * Class AttributeOptions
 *
 * Gmc\Catalog\Model
 * @api
 */
class AttributeOptions
{

    /**
     * @var \Magento\Framework\Db\Adapter\AdapterInterface
     */
    private $connection;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ProductAttributeRepository
     */
    private $attributeRepository;

    /**
     * AttributeOptions constructor
     *
     * @param ResourceConnection         $resource            ResourceConnection Object
     * @param LoggerInterface            $logger              LoggerInterface Object
     * @param ProductAttributeRepository $attributeRepository ProductAttributeRepository
     */
    public function __construct(
        ResourceConnection $resource,
        LoggerInterface $logger,
        ProductAttributeRepository $attributeRepository
    ) {
        $this->connection          = $resource->getConnection();
        $this->logger              = $logger;
        $this->attributeRepository = $attributeRepository;
    }//end __construct()

    /**
     * Function to retrieve attribute options by attribute codes
     *
     * @param string|array $attributeCode Attribute Codes
     * @param bool         $eav           bool
     * @return array
     */
    public function getAttrOpnsByCode($attributeCode, $eav = true)
    {
        $attributeOptions = [];
        try {
            if ($eav) {
                return $this->getOptionsArray(null, $attributeCode);
            }
            $attribute = $this->attributeRepository->get($attributeCode);
            if (!$attribute || !$attribute->usesSource()) {
                return $attributeOptions;
            }
            $options = $attribute->getSource()->getAllOptions();
            foreach ($options as $option) {
                $value                    = (string) $option['value'];
                $attributeOptions[$value] = (string) $option['label'];
            }
        } catch (\Exception $exception) {
            $this->logger->critical($exception);
        }

        return $attributeOptions;
    }//end getAttrOpnsByCode()

    /**
     * Function to retrieve attribute options by attribute codes
     *
     * @param string $attributeCode Attribute Code
     * @return array
     * @throws NoSuchEntityException
     */
    public function getAttributeOptionsByCode($attributeCode)
    {
        $attributeOptions = [];
        $attribute = $this->attributeRepository->get($attributeCode);
        if ($attribute && $attribute->usesSource()) {
            $options = $attribute->getSource()->getAllOptions();
            array_walk($options, function ($option) use (&$attributeOptions) {
                $value                    = (string) $option['value'];
                $attributeOptions[$value] = (string) $option['label'];
            });
        }
        return $attributeOptions;
    }//end getAttributeOptionsByCode()

    /**
     * Function to get attribute options array pairs
     *
     * @param int|array    $optionIds     Option Ids
     * @param string|array $attributeCode Attribute Codes
     * @return array
     */
    public function getOptionsArray($optionIds = null, $attributeCode = null)
    {
        $table = $this->connection->getTableName('eav_attribute_option_value');
        try {
            if (empty($optionIds) && empty($attributeCode)) {
                return [];
            }
            $optionIds     = is_array($optionIds) ? $optionIds : [$optionIds];
            $optionIds     = array_filter($optionIds);
            $attributeCode = is_array($attributeCode) ? $attributeCode : [$attributeCode];
            $attributeCode = array_filter($attributeCode);
            $select        = $this->connection->select()->from(
                ['option_value' => $table],
                [
                 'option_id',
                 'value',
                ]
            );
            if ($optionIds) {
                $select->where(
                    'option_value.option_id IN (?)',
                    $optionIds
                );
            }
            if ($attributeCode) {
                $select
                    ->joinInner(
                        ['option' => $this->connection->getTableName('eav_attribute_option')],
                        'option.option_id = option_value.option_id',
                        []
                    )->joinInner(
                        ['eav' => $this->connection->getTableName('eav_attribute')],
                        'eav.attribute_id = option.attribute_id',
                        []
                    )->where(
                        'eav.attribute_code IN (?)',
                        $attributeCode
                    );
            }
            $results = $this->connection->fetchPairs($select);

            return $results;
        } catch (\Exception $e) {
            $this->logger->critical($e);

            return [];
        }
    }//end getOptionsArray()
}//end class
