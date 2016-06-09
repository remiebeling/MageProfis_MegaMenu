<?php 
class MageProfis_MegaMenu_Helper_Data extends Mage_Core_Helper_Abstract 
{
    public $_first_level_categories;
    
    public function getFirstLevelCategories($attributes = '*')
    {
        if(is_null($this->_first_level_categories))
        {
            $this->_first_level_categories = Mage::getModel('catalog/category')
                    ->getCollection()
                    ->addAttributeToSelect($attributes)
                    ->addAttributeToFilter('include_in_menu', '1')
                    ->addAttributeToSort('position', 'ASC')
                    ->addIsActiveFilter()
                    ->addAttributeToFilter('level', '2');
        }    
        
        return $this->_first_level_categories;      
    }
    
    public function getCurrentCategoryId()
    {
        $cat = Mage::registry('current_category');
        return $cat ? $cat->getId() : false;
    }
}
