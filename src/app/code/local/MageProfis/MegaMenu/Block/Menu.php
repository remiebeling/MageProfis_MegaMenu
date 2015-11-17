<?php 
class MageProfis_MegaMenu_Block_Menu extends Mage_Core_Block_Template 
{
    public $_parent = null;
    public $_request = null;
    public $_categories = null;
    
    protected function _construct()
    {
        parent::_construct();
        $this->addData(array('cache_lifetime' => false)); // 12 hours
        $this->addCacheTag(array(
            Mage_Catalog_Model_Category::CACHE_TAG,
            Mage_Catalog_Model_Category::CACHE_TAG . '_' . $this->getParentCategoryId(),
        ));
    }
    
    public function getCacheKeyInfo()
    {
        return array(
            Mage_Catalog_Model_Category::CACHE_TAG . '_' . $this->getParentCategoryId(),
            $this->getNameInLayout(),
            Mage::app()->getStore()->getId(),
            Mage::getDesign()->getPackageName(),
            Mage::getDesign()->getTheme('template'),
        );
    }
            
    public function getCategories()
    {
        if($this->__categories == null)
        {
            $this->__categories = Mage::getModel('catalog/category')
                    ->getCollection()
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter('include_in_menu', '1')
                    ->addAttributeToSort('position', 'ASC')
                    ->addIsActiveFilter();
                    //->addIsVisibleFilter();    
        }    
        
        return $this->__categories;      
    }
    
    public function getAdditionalCategories($ids, $single = false)
    {
        $category_collection = Mage::getModel('catalog/category')->getCollection()
                ->addAttributeToSelect('*')
                ->addIdFilter($ids)
                ->addAttributeToSort('position', 'ASC')
                ->addIsActiveFilter()
                ->joinUrlRewrite();  
                
        foreach($category_collection as $_item)
        {
            $this->addCacheTag(Mage_Catalog_Model_Category::CACHE_TAG . '_' . $_item->getId());
        }       
              
        if($single)
        {
            return $category_collection->getFirstItem();    
        }                
        return $category_collection;
    }
    
    public function getSubCategoriesIds()
    {
        return $this->getParentCategory()->getChildren();
    }
    
    public function getSubCategories($category)
    {
        $children_ids = $category->getChildren();    
            
        $categoryCollection = Mage::getModel('catalog/category')->getCollection()
                ->addAttributeToSelect('*')
                ->addIdFilter($children_ids)
                ->addAttributeToFilter('include_in_menu', 1)
                ->addAttributeToSort('position', 'ASC')
                ->addIsActiveFilter()
                ->joinUrlRewrite();       
                
        foreach($categoryCollection as $_item)
        {
            $this->addCacheTag(Mage_Catalog_Model_Category::CACHE_TAG . '_' . $_item->getId());
        }  
                
        return $categoryCollection;          
    }
    
    public function getParentSetting($code)
    {
        $_parent = $this->getParentCategory();
        
        return $_parent->getData($code);            
    }
    
    public function getParentCategoryId()
    {
        if($this->_request == "")
        {
            $this->_request = Mage::app()->getRequest();
        }    
        $_parent_id = $this->_request->getParam('id');
        
        return $_parent_id;     
    }
    
    public function getParentCategory()
    {
        if($this->_parent == "")
        {
            if($this->_request == "")
            {
                $this->_request = Mage::app()->getRequest();
            }    
            $_parent_id = $this->_request->getParam('id');
            
            $this->_parent = Mage::getModel('catalog/category')->load($_parent_id);
        }
        return $this->_parent;
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
