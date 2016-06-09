<?php 
class MageProfis_MegaMenu_Block_Topmenu extends Mage_Core_Block_Template 
{
    public $_parent = null;
    public $_categories = null;
    
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
            'mageprofis_megamenu_topmenu',
        );
    }
            
    public function getCategories()
    {    
        return Mage::helper('megamenu')->getFirstLevelCategories();  
    }
    
    public function getCurrentPathIds()
    {
       if($current = Mage::registry('current_category'))
       {
            return $current->getPathIds();
       }   
       return array();     
    }
    
    public function getCategoryState($category)
    {
        $current_path = $this->getCurrentPathIds();
        
        if(in_array($category->getId(), $current_path))
        {
            return 'active';
        }
        return false;   
    }
}