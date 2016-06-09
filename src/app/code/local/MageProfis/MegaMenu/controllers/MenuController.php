<?php
class MageProfis_MegaMenu_MenuController extends Mage_Core_Controller_Front_Action 
{
    public function subcategoriesAction()
    {
        $this->loadLayout($this->getFullActionName());
        $this->renderLayout();
        
        return $this;
    }
}
