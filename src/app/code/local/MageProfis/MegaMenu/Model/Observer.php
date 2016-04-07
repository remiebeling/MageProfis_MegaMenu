<?php
class Mageprofis_MegaMenu_Model_Observer
{
    public function addLayoutXml($event)
    {
        $xml = $event->getUpdates()
                ->addChild('megamenu');
        $xml->addAttribute('module', 'MageProfis_MegaMenu');
        $xml->addChild('file', 'mageprofis-megamenu.xml');
    }
}
