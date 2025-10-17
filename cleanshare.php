<?php
/**
 *  2009-2025 Tecnoacquisti.com
 *
 *  For support feel free to contact us on our website at https://www.tecnoacquisti.com
 *
@author    Arte e Informatica <helpdesk@tecnoacquisti.com>
 *  @copyright 2009-2025 Arte e Informatica
 *  @license   One Paid Licence By WebSite Using This Module. No Rent. No Sell. No Share.
 *  @version   1.0
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class CleanShare extends Module
{
    public function __construct()
    {
        $this->name = 'cleanshare';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Tecnoacquisti.com';
        $this->need_instance = 0;
        parent::__construct();

        $this->displayName = $this->l('Clean Share');
        $this->description = $this->l('Simulate mobile share button using PrestaShop generated URLs, avoiding UTMs/parameters.');
        $this->ps_versions_compliancy = ['min' => '1.7.8.0', 'max' => _PS_VERSION_];
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('displayProductAdditionalInfo') &&
            $this->registerHook('showProductCustomized') &&
            $this->registerHook('actionFrontControllerSetMedia');
    }

    public function hookActionFrontControllerSetMedia($params)
    {

        $controller = $this->context->controller;
        if (!$controller) {
            return;
        }

        $controllerName = Tools::getValue('controller');
        if ($controllerName !== 'product') {
            return;
        }

        $controller->registerJavascript(
            'module-cleanshare-js',
            'modules/'.$this->name.'/views/js/cleanshare.js',
            [
                'position' => 'bottom',
                'priority' => 150,
                'attributes' => 'defer',
            ]
        );

        $controller->registerStylesheet(
            'module-cleanshare-css',
            'modules/'.$this->name.'/views/css/cleanshare.css',
            [
                'media' => 'all',
                'priority' => 150,
            ]
        );
    }

    public function shareLink($params)
    {

        $product = isset($params['product']) ? $params['product'] : null;

        if ($product && isset($product->id)) {
            $id_product = (int)$product->id;
            $cleanUrl = $this->context->link->getProductLink($id_product, null, null, null, $this->context->language->id);
        } else {
            $cleanUrl = $this->context->link->getPageLink($this->context->controller->php_self, true, $this->context->language->id);
        }

        $this->context->smarty->assign([
            'cleanshare_clean_url' => $cleanUrl,
            'cleanshare_button_text' => $this->l('Share')
        ]);

        return $this->display(__FILE__, 'views/templates/hook/cleanshare_button.tpl');
    }


    public function hookDisplayProductAdditionalInfo($params)
    {
        return $this->shareLink($params);
    }

    public function hookShowProductCustomized($params)
    {
        return $this->shareLink($params);
    }

}
