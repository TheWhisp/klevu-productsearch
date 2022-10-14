<?php

namespace Klevu\Search\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CreateRatingProductAttribute implements DataPatchInterface
{
    const ATTRIBUTE_CODE = 'rating';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @return string[]
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @return $this|CreateRatingProductAttribute
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        if (!($eavSetup->getAttributeId(Product::ENTITY, static::ATTRIBUTE_CODE))) {
            $eavSetup->addAttribute(
                Product::ENTITY,
                static::ATTRIBUTE_CODE,
                [
                    'type' => 'varchar',
                    'input' => 'text',
                    'label' => 'Rating',
                    'global' => 0,
                    'required' => false,
                    'user_defined' => true,
                    'group' => 'Product Details',
                    'sort_order' => 5,
                    'note' => 'Automatically calculated and populated by Klevu to sync ratings data. ' .
                        'For more information, please refer to the Klevu knowledgebase.'
                ]
            );
        }
        $this->moduleDataSetup->endSetup();

        return $this;
    }
}
