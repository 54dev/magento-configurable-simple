<?php

class OrganicInternet_SimpleConfigurableProducts_Catalog_Block_Product_View_Type_Configurable
    extends Mage_Catalog_Block_Product_View_Type_Configurable
{
    public function getJsonConfig()
    {
        $config = Zend_Json::decode(parent::getJsonConfig());

        //childProducts is an array of productID => price.
        $childProducts = array();
        $childProductTierPriceHtml = array();
        $childBlock = $this->getLayout()->createBlock('catalog/product_view');

        //Create the extra price and tier price data/html we need.
        foreach ($this->getAllowProducts() as $product) {
            $productId  = $product->getId();
            $childProducts[$productId] = $this->_registerJsPrice($this->_convertPrice($product->getFinalPrice()));
            if (count($childBlock->getTierPrices($product))) {
                $childProductTierPriceHtml[$productId] = $childBlock->getTierPriceHtml($product);
            }
        }

        //Remove any existing option prices.
        //Removing holes out of existing arrays is not nice,
        //but it keeps the extension's code separate so if Varien's getJsonConfig
        //is added to, things should still work.
        if (is_array($config['attributes'])) {
            foreach ($config['attributes'] as $attributeID => &$info) {
                if (is_array($info['options'])) {
                    foreach ($info['options'] as &$option) {
                        unset($option['price']);
                    }
                    unset($option); //clear foreach var ref
                }
            }
            unset($info); //clear foreach var ref
        }

        $config['childProducts'] = $childProducts;
        $config['priceFromLabel'] = Mage::helper('catalog')->__('Price From:');
        $config['childProductTierPriceHtml'] = $childProductTierPriceHtml;

        //Mage::log($config);
        return Zend_Json::encode($config);
    }
}
