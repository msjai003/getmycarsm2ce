<?php
declare(strict_types=1);
namespace Gmc\LoginAuth\Setup\Patch\Data;

use Psr\Log\LoggerInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Api\AttributeRepositoryInterface as AttributeRepository;
use Magento\Customer\Model\ResourceModel\Attribute;

/**
 * Class CreateCustomerAttribute
 * Gmc\LoginAuth\Setup\Patch\Data
 */
class CreateCustomerAttribute implements DataPatchInterface
{
    // Constants
    const ATTRIBUTE_CODE = 'mobile_number';

    /**
     * CreateCustomerAttribute constructor
     *
     * @param LoggerInterface $logger
     * @param CustomerSetupFactory $customerSetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param AttributeRepository $moduleDataSetup
     * @param Attribute $moduleDataSetup
     */
    public function __construct(
        private LoggerInterface $logger,
        private CustomerSetupFactory $customerSetupFactory,
        private ModuleDataSetupInterface $moduleDataSetup,
        private AttributeRepository $attributeRepository,
        private Attribute $attribute
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        try {
            /** @var \Magento\Customer\Setup\CustomerSetup $customerSetup */
            $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
            
            $customerSetup->removeAttribute(Customer::ENTITY, self::ATTRIBUTE_CODE);
            $customerEntity      = $customerSetup->getEavConfig()->getEntityType('customer');            
            $attributeSetId      = $customerEntity->getDefaultAttributeSetId();

            $option['type']   = 'varchar';
            $option['label']   = 'Mobile Number';
            $option['input']   = 'text';
            $option['attribute_set_id']   = $attributeSetId;
            $option['attribute_group_id'] = $attributeSetId;
            $option['visible']            = true;
            $option['required']           = true;
            $option['system']             = false;
            $option['user_defined']       = true;
            $option['visible_on_front']   = true;
            $option['position']           = 105;   
            
            $customerSetup->addAttribute(
                Customer::ENTITY,
                self::ATTRIBUTE_CODE,
                $option
            );       
            
            $createdAttr = $customerSetup->getEavConfig()->getAttribute(
                Customer::ENTITY,
                self::ATTRIBUTE_CODE
            );

            $createdAttr->addData(
                [
                    'is_visible'         => true,
                    'attribute_set_id'   => $attributeSetId,
                    'attribute_group_id' => $attributeSetId,                    
                    'used_in_forms'      => [
                    'customer_account_create',
                    'customer_account_edit',
                    'adminhtml_customer',
                    ],
                ]
            );
            $this->attribute->save($createdAttr);
            $this->attributeRepository->save($createdAttr);            
        } catch (\Exception $ex) {
            $this->logger->critical('Error message', ['exception' => $ex]);
        }
        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}//end class UpdateCustomerAttribute
