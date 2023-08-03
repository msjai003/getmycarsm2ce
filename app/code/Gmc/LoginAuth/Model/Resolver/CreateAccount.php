<?php

namespace Gmc\LoginAuth\Model\Resolver;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Customer\Api\GroupRepositoryInterface;

class CreateAccount implements ResolverInterface
{
    const FIRSTNAME = 'firstname';
    const LASTNAME  = '.';
    const PARTNER_GROUP = 'Partners';

    /**
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerInterfaceFactory $customerFactory
     * @param GroupRepositoryInterface $groupRepository
     */
    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
        private CustomerInterfaceFactory $customerFactory,
        private GroupRepositoryInterface $groupRepository
    ) {}

    public function resolve(
        Field $field,
              $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    )
    {
        $name = $args['input']['name'];
        $mobileNumber = $args['input']['mobileNumber'];

        // Fix constant values
        $lastName  = self::LASTNAME;

        // Generate email using mobile
        $emailFormat = '%s@example.com';
        $email = sprintf($emailFormat, $mobileNumber);

        // Get Customer Group ID
//        $customerGroup = $this->groupRepository->getList(['customer_group_code' => self::PARTNER_GROUP]);
//        $groupId = $customerGroup->getId();

        try {
            // Create a new customer
            $customer = $this->customerFactory->create();
            $customer->setFirstname($name);
            $customer->setLastname($lastName);
            $customer->setEmail($email);
            $customer->setCustomAttribute('mobile_number', $mobileNumber);
            $customer->setConfirmation(null);
            $customer->setGroupId(4);

            $customer = $this->customerRepository->save($customer);
        } catch (LocalizedException $e) {
            throw new GraphQlInputException(__('Customer with the given mobile number already exists.'));
        }

        // Return the created customer data
        $output = [
            'customer' => [
                'id' => $customer->getId()
            ],
        ];

        return $output;
    }
}
