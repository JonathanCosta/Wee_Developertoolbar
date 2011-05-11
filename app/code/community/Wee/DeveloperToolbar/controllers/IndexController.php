<?php

class Cdb_DeveloperToolbar_IndexController extends Mage_Core_Controller_Front_Action
{
	const SHOP_SCOPE = 'stores';
	
    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();

            $frontendHintsEnable = false;
            if (isset($postData['frontendHints']) && $postData['frontendHints']) {
                $frontendHintsEnable = true;
            }
          
            Mage::getConfig()->saveConfig('dev/debug/template_hints', $frontendHintsEnable, self::SHOP_SCOPE, Mage::app()->getStore()->getStoreId());
            Mage::getConfig()->saveConfig('dev/debug/template_hints_blocks', $frontendHintsEnable, self::SHOP_SCOPE, Mage::app()->getStore()->getStoreId());
          
            $translateInlineEnabled = false;
            if (isset($postData['translateInline']) && $postData['translateInline']) {
                $translateInlineEnabled = true;
            }

            Mage::getConfig()->saveConfig('dev/translate_inline/active', $translateInlineEnabled, self::SHOP_SCOPE, Mage::app()->getStore()->getStoreId());
  
            if (isset($postData['clearCache']) && $postData['clearCache']) {
                self::clearCache();
            }

            $this->_redirectReferer();
        }
    }
    
    static protected function clearCache()
    {
        Mage::app()->getCacheInstance()->flush();
        Mage::app()->cleanCache();
        Mage::getModel('core/design_package')->cleanMergedJsCss();
        Mage::getModel('catalog/product_image')->clearCache();

        $cacheTypes = array_keys(Mage::helper('core')->getCacheTypes());
        $enable = array();
        foreach ($cacheTypes as $type) {            
            $enable[$type] = 0;                 
        }
        Mage::app()->saveUseCache($enable);
    }
}