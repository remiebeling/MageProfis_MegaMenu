<?php 
class MageProfis_MegaMenu_Block_Submenu extends Mage_Core_Block_Template 
{
    public $_parent = null;
    public $_categories = null;
    
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('mageprofis/megamenu/subcategories.phtml');
        //
        // Cached via Text/List.php
        //
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
                
        return $categoryCollection;          
    }
    
    public function getParentCategory()
    {
        if($this->_parent == "")
        { 
            $_parent_id = $this->getParentCategoryId();
            
            $this->_parent = Mage::getModel('catalog/category')->load($_parent_id);
        }
        return $this->_parent;
    }
    

}
