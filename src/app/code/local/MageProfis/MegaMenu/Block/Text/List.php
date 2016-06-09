<?php

class MageProfis_MegaMenu_Block_Text_List
extends Mage_Core_Block_Abstract
{
    const MAGEPROFIS_MEGAMENU_BLOCK_PREFIX = 'mageprofis_megamenu_subcategory_';
    
    protected function _construct()
    {
        parent::_construct();
        $this->addData(array('cache_lifetime' => false)); // 12 hours
        $this->addCacheTag(array(
            Mage_Catalog_Model_Category::CACHE_TAG,
            Mage_Catalog_Model_Category::CACHE_TAG . '_' . Mage::helper('megamenu')->getCurrentCategoryId(),
        ));
    }
    
    public function getCacheKeyInfo()
    {
        return array(
            Mage_Catalog_Model_Category::CACHE_TAG . '_' . Mage::helper('megamenu')->getCurrentCategoryId(),
            $this->getNameInLayout(),
            Mage::app()->getStore()->getId(),
            Mage::getDesign()->getPackageName(),
            Mage::getDesign()->getTheme('template'),
            'mageprofis_megamenu_submenu_json',
        );
    }

    /**
     * 
     * @return string
     */
    protected function _toHtml()
    {
        $this->collectSubmenus();
        
        $result = array(
            'items' => array()
        );
        foreach ($this->getSortedChildren() as $name) {
            $block = $this->getLayout()->getBlock($name);
            /* @var $block Mage_Core_Block_Abstract */
            if (!$block) {
                Mage::throwException(Mage::helper('core')->__('Invalid block: %s', $name));
            }
            $block->setProduct($this->getProduct());
            $result['items'][] = array(
                'id'   => str_replace(self::MAGEPROFIS_MEGAMENU_BLOCK_PREFIX, '', $block->getBlockAlias()),
                'content' => $block->toHtml()
            );
        }
        return Mage::helper('core')->jsonEncode($result);
    }

    /**
     * add an attribute to json
     * 
     * @param string $attribute
     * @param string $alias
     * @param string $name
     * 
     * @return Loewenstark_Configurablechanger_Block_Text_List
     */
    public function collectSubmenus()
    {
        $first_level_categories = Mage::helper('megamenu')->getFirstLevelCategories();
        
        foreach($first_level_categories as $first_level_category)
        {
            $id = $first_level_category->getId();
            $name = self::MAGEPROFIS_MEGAMENU_BLOCK_PREFIX . $id;
            $alias = $name;
            $block = $this->getLayout()->createBlock('megamenu/submenu', $name)->setParentCategoryId($id);
            $this->append($block, $alias);
        }

        return $this;
    }
    
    
    /**
     * Add tag to block
     *
     * @param string|array $tag
     * @return Mage_Core_Block_Abstract
     */
    public function addCacheTag($tag)
    {
        if (method_exists('Mage_Core_Block_Abstract', 'addCacheTag'))
        {
            return parent::addCacheTag($tag);
        }
        if (!is_array($tag))
        {
            $tag = array($tag);
        }
        $this->addData(array(
            'cache_tags'    => $tag
        ));
        return $this;
    }
}