<?php

namespace BzoTech\Megamenu\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Catalog\Model\Category\Attribute\Backend\Image;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use BzoTech\Megamenu\Model\Category\Column;
use BzoTech\Megamenu\Model\Category\Label;
use BzoTech\Megamenu\Model\Category\Align;
use Magento\Eav\Model\Config;

class InstallData implements InstallDataInterface
{
    protected $eav_setup;
    protected $connection;

    public function __construct(
        private EavSetupFactory $eavSetupFactory,
        \Magento\Framework\App\ResourceConnection $connection,
        private Config $eavConfig
    )
    {
        //$this->eav_setup_factory = $eavSetupFactory;
        $this->connection        = $connection->getConnection();
        //$this->eav_config        = $eavConfig;
    }

    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    )
    {
        $setup->startSetup();

        //create Category Attributes
        $this->createCategoryAttributes($setup);

        $setup->endSetup();
    }

    protected function createCategoryAttributes($setup)
    {
        $eav_setup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'enable_megamenu',
            [
                'type' => 'int',
                'label' => 'Enable Megamenu',
                'input' => 'select',
                'source' => Boolean::class,
                'sort_order' => 5,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu General',
                'backend' => ''
            ]
        );

        $eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'dropdown_width',
            [
                'type' => 'text',
                'label' => 'Dropdown Width(px)',
                'input' => 'text',
                'sort_order' => 10,
                'source' => '',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '940px',
                'group' => 'Megamenu General',
                'backend' => ''
            ]
        );

        $eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'custom_url',
            [
                'type' => 'text',
                'label' => 'Custom URL',
                'input' => 'text',
                'sort_order' => 12,
                'source' => '',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'group' => 'Megamenu General',
                'backend' => ''
            ]
        );

        /*$eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'align_container',
            [
                'type' => 'int',
                'label' => 'Align Container',
                'input' => 'select',
                'source' => Boolean::class,
                'sort_order' => 13,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu General',
                'backend' => ''
            ]
        );*/

        $eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'dropdown_align',
            [
                'type' => 'varchar',
                'label' => 'Label',
                'input' => 'select',
                'sort_order' => 13,
                'source' => Align::class,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'group' => 'Megamenu General',
                'backend' => ''
            ]
        );

        $eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'float_right',
            [
                'type' => 'int',
                'label' => 'Float Right',
                'input' => 'select',
                'source' => Boolean::class,
                'sort_order' => 14,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu General',
                'backend' => ''
            ]
        );

        $eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'custom_class',
            [
                'type' => 'text',
                'label' => 'Dropdown Width(px)',
                'input' => 'text',
                'sort_order' => 15,
                'source' => '',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'group' => 'Megamenu General',
                'backend' => ''
            ]
        );

        $eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'icon_before',
            [
                'type' => 'varchar',
                'label' => 'Image',
                'input' => 'image',
                'backend' => Image::class,
                'required' => false,
                'sort_order' => 16,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu General'
            ]
        );

        $eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'item_label',
            [
                'type' => 'varchar',
                'label' => 'Label',
                'input' => 'select',
                'sort_order' => 17,
                'source' => Label::class,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'group' => 'Megamenu General',
                'backend' => ''
            ]
        );

        $eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'enable_content_top',
            [
                'type' => 'int',
                'label' => 'Enable Content Top',
                'input' => 'select',
                'source' => Boolean::class,
                'sort_order' => 18,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu Content',
                'backend' => ''
            ]
        );

        $eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'content_top',
            [
                'type' => 'text',
                'label' => 'Content Top',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 20,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'wysiwyg_enabled' => true,
                'is_html_allowed_on_front' => true,
                'group' => 'Megamenu Content',
            ]
        );

        $eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'enable_content_left',
            [
                'type' => 'int',
                'label' => 'Enable Content Left',
                'input' => 'select',
                'source' => Boolean::class,
                'sort_order' => 25,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu Content',
                'backend' => ''
            ]
        );

        $eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'content_left',
            [
                'type' => 'text',
                'label' => 'Content Left',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 30,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'wysiwyg_enabled' => true,
                'is_html_allowed_on_front' => true,
                'group' => 'Megamenu Content',
            ]
        );

        $eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'enable_content_right',
            [
                'type' => 'int',
                'label' => 'Enable Content Right',
                'input' => 'select',
                'source' => Boolean::class,
                'sort_order' => 35,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu Content',
                'backend' => ''
            ]
        );

        $eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'content_right',
            [
                'type' => 'text',
                'label' => 'Content Right',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 40,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'wysiwyg_enabled' => true,
                'is_html_allowed_on_front' => true,
                'group' => 'Megamenu Content',
            ]
        );

        $eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'enable_content_bottom',
            [
                'type' => 'int',
                'label' => 'Enable Content Bottom',
                'input' => 'select',
                'source' => Boolean::class,
                'sort_order' => 45,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu Content',
                'backend' => ''
            ]
        );

        $eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'content_bottom',
            [
                'type' => 'text',
                'label' => 'Content Bottom',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 50,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'wysiwyg_enabled' => true,
                'is_html_allowed_on_front' => true,
                'group' => 'Megamenu Content',
            ]
        );

        $eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'col_left_width',
            [
                'type' => 'text',
                'label' => 'Column Left Width(%)',
                'input' => 'text',
                'sort_order' => 60,
                'source' => '',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '30',
                'group' => 'Megamenu Layout',
                'backend' => ''
            ]
        );

        $eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'number_cat_column',
            [
                'type' => 'varchar',
                'label' => 'Category Column(Number 1 -> 10)',
                'input' => 'select',
                'sort_order' => 70,
                'source' => Column::class,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '2',
                'group' => 'Megamenu Layout',
                'backend' => ''
            ]
        );

        $eav_setup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'col_right_width',
            [
                'type' => 'text',
                'label' => 'Column Right Width(%)',
                'input' => 'text',
                'sort_order' => 80,
                'source' => '',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '30',
                'group' => 'Megamenu Layout',
                'backend' => ''
            ]
        );
    }
}
