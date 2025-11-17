<?php
/**
 *  2009-2025 Tecnoacquisti.com
 *
 *  For support feel free to contact us on our website at https://www.tecnoacquisti.com
 *
 *  @author    Arte e Informatica <helpdesk@tecnoacquisti.com>
 *  @copyright 2009-2025 Arte e Informatica
 *  @license   One Paid Licence By WebSite Using This Module. No Rent. No Sell. No Share.
 *
 *  @version   1.0
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class CleanShare extends Module
{
    public const CONF_FLOAT_ENABLED = 'CLEANSHARE_FLOAT_ENABLED';
    public const CONF_FLOAT_POSITION = 'CLEANSHARE_FLOAT_POSITION';
    public const CONF_FLOAT_BOTTOM = 'CLEANSHARE_FLOAT_BOTTOM';
    public const CONF_FLOAT_ZINDEX = 'CLEANSHARE_FLOAT_ZINDEX';
    public const CONF_BUTTON_POSITION = 'CLEANSHARE_BUTTON_POSITION';
    public const CONF_FLOAT_BG = 'CLEANSHARE_FLOAT_BG';
    public const CONF_FLOAT_TEXT = 'CLEANSHARE_FLOAT_TEXT';
    public const CONF_FLOAT_BG_HOVER = 'CLEANSHARE_FLOAT_BG_HOVER';
    public const CONF_FLOAT_TEXT_HOVER = 'CLEANSHARE_FLOAT_TEXT_HOVER';

    public function __construct()
    {
        $this->name = 'cleanshare';
        $this->tab = 'front_office_features';
        $this->version = '1.0.2';
        $this->author = 'Tecnoacquisti.com';
        $this->need_instance = 0;
        parent::__construct();

        $this->displayName = $this->l('Clean Share');
        $this->description = $this->l('Simulate mobile share button using PrestaShop generated URLs, avoiding UTMs/parameters.');
        $this->ps_versions_compliancy = ['min' => '1.7.8.0', 'max' => _PS_VERSION_];
    }

    public function install()
    {
        // valori di default
        Configuration::updateValue(self::CONF_FLOAT_ENABLED, '1');
        Configuration::updateValue(self::CONF_FLOAT_POSITION, 'right');
        Configuration::updateValue(self::CONF_FLOAT_BOTTOM, '24');
        Configuration::updateValue(self::CONF_FLOAT_ZINDEX, '9999');
        Configuration::updateValue(self::CONF_BUTTON_POSITION, 'displayProductAdditionalInfo');
        Configuration::updateValue(self::CONF_FLOAT_BG, '#2196F3');            // blu default
        Configuration::updateValue(self::CONF_FLOAT_TEXT, '#ffffff');          // testo bianco
        Configuration::updateValue(self::CONF_FLOAT_BG_HOVER, '#1976D2');      // blu scuro hover
        Configuration::updateValue(self::CONF_FLOAT_TEXT_HOVER, '#ffffff');    // testo hover bianco

        return parent::install()
            && $this->registerHook('displayProductAdditionalInfo')
            && $this->registerHook('showProductCustomized')
            && $this->registerHook('displayHeader')
            && $this->registerHook('actionFrontControllerSetMedia');
    }

    public function hookActionFrontControllerSetMedia($params)
    {
        $controller = $this->context->controller;
        if (!$controller) {
            return;
        }

        $controller->registerJavascript(
            'module-cleanshare-js',
            'modules/' . $this->name . '/views/js/cleanshare.js',
            [
                'position' => 'bottom',
                'priority' => 150,
                'attributes' => 'defer',
            ]
        );

        $controller->registerStylesheet(
            'module-cleanshare-css',
            'modules/' . $this->name . '/views/css/cleanshare.css',
            [
                'media' => 'all',
                'priority' => 150,
            ]
        );
    }

    // pagina di configurazione backend
    public function getContent()
    {
        $output = '';

        // carico eventuali asset BO (CSS per uniformare PS8/PS9)
        $this->loadBackOfficeAssets();

        if (Tools::isSubmit('submitCleanshareModule')) {
            $output .= $this->processConfigurationForm();
        }

        $output .= $this->renderConfigurationForm();
        $output .= $this->renderCreditsBlock();

        return $output;
    }

    /**
     * Gestisce il salvataggio delle configurazioni dal form di BO.
     *
     * @return string HTML del messaggio di conferma/errore
     */
    protected function processConfigurationForm()
    {
        // normalize + validazione
        $floatEnabled   = Tools::getValue(self::CONF_FLOAT_ENABLED) ? '1' : '0';

        $posCandidate   = strtolower((string) Tools::getValue(self::CONF_FLOAT_POSITION));
        $allowedPos     = ['left', 'right', 'centered'];
        $floatPosition  = in_array($posCandidate, $allowedPos, true) ? $posCandidate : 'right';

        // Floating bottom (px)
        $bottomRaw = Tools::getValue(self::CONF_FLOAT_BOTTOM);
        if (Validate::isUnsignedInt($bottomRaw)) {
            $floatBottom = (int) $bottomRaw;
        } else {
            $floatBottom = 20;
        }

        // Floating z-index
        $zRaw = Tools::getValue(self::CONF_FLOAT_ZINDEX);
        if (Validate::isUnsignedInt($zRaw)) {
            $floatZ = (int) $zRaw;
        } else {
            $floatZ = 9999;
        }

        $buttonPosCandidate = (string) Tools::getValue(self::CONF_BUTTON_POSITION);
        $allowedHooks       = ['displayProductAdditionalInfo', 'showProductCustomized', 'disabled'];
        $buttonPos          = in_array($buttonPosCandidate, $allowedHooks, true)
            ? $buttonPosCandidate
            : 'displayProductAdditionalInfo';

        $floatBg        = $this->sanitizeColor(Tools::getValue(self::CONF_FLOAT_BG), '#2196F3');
        $floatText      = $this->sanitizeColor(Tools::getValue(self::CONF_FLOAT_TEXT), '#ffffff');
        $floatBgHover   = $this->sanitizeColor(Tools::getValue(self::CONF_FLOAT_BG_HOVER), '#1976D2');
        $floatTextHover = $this->sanitizeColor(Tools::getValue(self::CONF_FLOAT_TEXT_HOVER), '#ffffff');

        // persistenza
        Configuration::updateValue(self::CONF_FLOAT_ENABLED, $floatEnabled);
        Configuration::updateValue(self::CONF_FLOAT_POSITION, $floatPosition);
        Configuration::updateValue(self::CONF_FLOAT_BOTTOM, $floatBottom);
        Configuration::updateValue(self::CONF_FLOAT_ZINDEX, $floatZ);
        Configuration::updateValue(self::CONF_BUTTON_POSITION, $buttonPos);
        Configuration::updateValue(self::CONF_FLOAT_BG, $floatBg);
        Configuration::updateValue(self::CONF_FLOAT_TEXT, $floatText);
        Configuration::updateValue(self::CONF_FLOAT_BG_HOVER, $floatBgHover);
        Configuration::updateValue(self::CONF_FLOAT_TEXT_HOVER, $floatTextHover);

        return $this->displayConfirmation($this->l('Settings saved'));
    }

    /**
     * Sanitizza un colore esadecimale, con fallback.
     *
     * @param string|null $value
     * @param string      $fallback
     *
     * @return string
     */
    protected function sanitizeColor($value, $fallback)
    {
        if (is_string($value)
            && preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $value)
        ) {
            return $value;
        }

        return $fallback;
    }

    /**
     * Restituisce la definizione del form di configurazione.
     *
     * @return array
     */
    protected function getConfigForm()
    {
        return [
            'form' => [
                'legend' => [
                    'title' => $this->l('CleanShare settings'),
                    'icon'  => 'icon-share',
                ],
                'input' => [
                    [
                        'type'    => 'switch',
                        'label'   => $this->l('Enable floating button'),
                        'name'    => self::CONF_FLOAT_ENABLED,
                        'is_bool' => true,
                        'values'  => [
                            [
                                'id'    => 'active_on',
                                'value' => '1',
                                'label' => $this->l('Enabled'),
                            ],
                            [
                                'id'    => 'active_off',
                                'value' => '0',
                                'label' => $this->l('Disabled'),
                            ],
                        ],
                    ],
                    [
                        'type'  => 'color',
                        'label' => $this->l('Background color'),
                        'name'  => self::CONF_FLOAT_BG,
                        'size'  => 7,
                    ],
                    [
                        'type'  => 'color',
                        'label' => $this->l('Text color'),
                        'name'  => self::CONF_FLOAT_TEXT,
                        'size'  => 7,
                    ],
                    [
                        'type'  => 'color',
                        'label' => $this->l('Background color (hover)'),
                        'name'  => self::CONF_FLOAT_BG_HOVER,
                        'size'  => 7,
                    ],
                    [
                        'type'  => 'color',
                        'label' => $this->l('Text color (hover)'),
                        'name'  => self::CONF_FLOAT_TEXT_HOVER,
                        'size'  => 7,
                    ],
                    [
                        'type'  => 'select',
                        'label' => $this->l('Floating position'),
                        'name'  => self::CONF_FLOAT_POSITION,
                        'options' => [
                            'query' => [
                                ['id' => 'left',     'name' => $this->l('Left')],
                                ['id' => 'centered', 'name' => $this->l('Centered')],
                                ['id' => 'right',    'name' => $this->l('Right')],
                            ],
                            'id'   => 'id',
                            'name' => 'name',
                        ],
                    ],
                    [
                        'type'  => 'text',
                        'label' => $this->l('Floating bottom (px)'),
                        'name'  => self::CONF_FLOAT_BOTTOM,
                        'size'  => 6,
                        'hint'  => $this->l('Enter a non-negative integer value (pixels from the bottom).'),
                    ],
                    [
                        'type'  => 'text',
                        'label' => $this->l('Floating z-index'),
                        'name'  => self::CONF_FLOAT_ZINDEX,
                        'size'  => 6,
                        'hint'  => $this->l('Higher values keep the button on top of other elements. Non-negative integer.'),
                    ],
                    [
                        'type'  => 'select',
                        'label' => $this->l('Position for the inline share button'),
                        'name'  => self::CONF_BUTTON_POSITION,
                        'options' => [
                            'query' => [
                                [
                                    'id'   => 'displayProductAdditionalInfo',
                                    'name' => $this->l('displayProductAdditionalInfo'),
                                ],
                                [
                                    'id'   => 'showProductCustomized',
                                    'name' => $this->l('showProductCustomized'),
                                ],
                                [
                                    'id'   => 'disabled',
                                    'name' => $this->l('Disabled'),
                                ],
                            ],
                            'id'   => 'id',
                            'name' => 'name',
                        ],
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-primary float-end',
                ],
            ],
        ];
    }

    /**
     * Valori attuali di configurazione per il form.
     *
     * @return array
     */
    protected function getConfigFormValues()
    {
        return [
            self::CONF_FLOAT_ENABLED     => Configuration::get(self::CONF_FLOAT_ENABLED),
            self::CONF_FLOAT_POSITION    => Configuration::get(self::CONF_FLOAT_POSITION),
            self::CONF_FLOAT_BOTTOM      => Configuration::get(self::CONF_FLOAT_BOTTOM),
            self::CONF_FLOAT_ZINDEX      => Configuration::get(self::CONF_FLOAT_ZINDEX),
            self::CONF_BUTTON_POSITION   => Configuration::get(self::CONF_BUTTON_POSITION),
            self::CONF_FLOAT_BG          => Configuration::get(self::CONF_FLOAT_BG),
            self::CONF_FLOAT_TEXT        => Configuration::get(self::CONF_FLOAT_TEXT),
            self::CONF_FLOAT_BG_HOVER    => Configuration::get(self::CONF_FLOAT_BG_HOVER),
            self::CONF_FLOAT_TEXT_HOVER  => Configuration::get(self::CONF_FLOAT_TEXT_HOVER),
        ];
    }

    /**
     * Render del form di configurazione (HelperForm).
     *
     * @return string
     */
    protected function renderConfigurationForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar      = false;
        $helper->show_cancel_button = false;
        $helper->module            = $this;
        $helper->name_controller   = $this->name;
        $helper->identifier        = $this->identifier;
        $helper->token             = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex      = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->submit_action     = 'submitCleanshareModule';

        $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = (int) Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        $helper->languages = $this->context->controller->getLanguages();
        $helper->id_language = $this->context->language->id;

        $helper->fields_value = $this->getConfigFormValues();

        return $helper->generateForm([$this->getConfigForm()]);
    }

    /**
     * Render del blocco credit / copyright.
     *
     * @return string
     */
    protected function renderCreditsBlock()
    {
        $useSsl = (bool) Configuration::get('PS_SSL_ENABLED_EVERYWHERE')
            || (bool) Configuration::get('PS_SSL_ENABLED');

        $shopBaseUrl = $this->context->link->getBaseLink(
            (int) $this->context->shop->id,
            $useSsl
        );

        if ($this->context->smarty->getTemplateVars('module_dir') === null) {
            $this->context->smarty->assign('module_dir', $this->_path);
        }

        $this->context->smarty->assign([
            'shop_base_url' => $shopBaseUrl,
        ]);

        return $this->context->smarty->fetch(
            $this->local_path.'views/templates/admin/copyright.tpl'
        );
    }

    protected function loadBackOfficeAssets()
    {
        if (isset($this->context->controller) && method_exists($this->context->controller, 'addCSS')) {
            $this->context->controller->addCSS($this->_path.'views/css/admin.css');
        }
    }

    public function shareLink($params)
    {
        $product = isset($params['product']) ? $params['product'] : null;

        if ($product && isset($product->id)) {
            $id_product = (int) $product->id;
            $cleanUrl = $this->context->link->getProductLink($id_product, null, null, null, $this->context->language->id);
        } else {
            $cleanUrl = $this->context->link->getPageLink($this->context->controller->php_self, true, $this->context->language->id);
        }

        $this->context->smarty->assign([
            'cleanshare_clean_url' => $cleanUrl,
            'cleanshare_button_text' => $this->l('Share'),
        ]);

        return $this->display(__FILE__, 'views/templates/hook/cleanshare_button.tpl');
    }

    public function shareLinkFloat($params)
    {
        $link = $this->context->link;
        $id_lang = $this->context->language->id;
        $cleanUrl = '';

        $controllerName = isset($this->context->controller->php_self)
            ? $this->context->controller->php_self
            : Tools::getValue('controller');

        // se siamo sulla pagina prodotto, prova a ottenere l'id prodotto da GET o dal controller
        $pageProductId = 0;
        if ($controllerName === 'product') {
            $pageProductId = (int) Tools::getValue('id_product', 0);
            if (!$pageProductId && !empty($this->context->controller->product) && isset($this->context->controller->product->id)) {
                $pageProductId = (int) $this->context->controller->product->id;
            }
        }

        // preferisci il product passato nei params (hook prodotto), poi l'id rilevato dalla pagina
        if (!empty($params['product']) && isset($params['product']->id)) {
            $id_product = (int) $params['product']->id;
            $cleanUrl = $link->getProductLink($id_product, null, null, null, $id_lang);
        } elseif ($pageProductId) {
            $cleanUrl = $link->getProductLink($pageProductId, null, null, null, $id_lang);
        } else {
            // category
            if ($controllerName === 'category') {
                $id_category = (int) Tools::getValue('id_category', 0);
                if ($id_category) {
                    $cleanUrl = $link->getCategoryLink($id_category, null, $id_lang);
                }
            }
            // cms
            elseif ($controllerName === 'cms') {
                $id_cms = (int) Tools::getValue('id_cms', 0);
                if ($id_cms) {
                    if (class_exists('CMS')) {
                        $cms = new CMS($id_cms, $id_lang);
                        if (!empty($cms->id)) {
                            $cleanUrl = $link->getCMSLink($cms, $cms->link_rewrite, $id_lang);
                        }
                    }
                    if (empty($cleanUrl)) {
                        $cleanUrl = $link->getCMSLink($id_cms, null, $id_lang);
                    }
                }
            }
            // manufacturer / brand
            elseif ($controllerName === 'manufacturer' || $controllerName === 'brand') {
                $id_man = (int) Tools::getValue('id_manufacturer', Tools::getValue('id_brand', 0));
                if ($id_man) {
                    if (class_exists('Manufacturer')) {
                        $man = new Manufacturer($id_man, $id_lang);
                        if (!empty($man->id)) {
                            $cleanUrl = $link->getManufacturerLink($man, $man->link_rewrite, $id_lang);
                        }
                    }
                    if (empty($cleanUrl)) {
                        $cleanUrl = $link->getManufacturerLink($id_man, null, $id_lang);
                    }
                }
            }
            // supplier
            elseif ($controllerName === 'supplier') {
                $id_sup = (int) Tools::getValue('id_supplier', 0);
                if ($id_sup) {
                    if (class_exists('Supplier')) {
                        $sup = new Supplier($id_sup, $id_lang);
                        if (!empty($sup->id)) {
                            $cleanUrl = $link->getSupplierLink($sup, $sup->link_rewrite, $id_lang);
                        }
                    }
                    if (empty($cleanUrl)) {
                        $cleanUrl = $link->getSupplierLink($id_sup, null, $id_lang);
                    }
                }
            }
            // controller di un modulo (es. blog/module front controller)
            elseif (!empty($this->context->controller->module) && isset($this->context->controller->module->name)) {
                $moduleName = $this->context->controller->module->name;
                $moduleController = $controllerName ?: Tools::getValue('controller');

                $paramsToPass = $_GET;
                unset($paramsToPass['controller'], $paramsToPass['module'], $paramsToPass['fc']);

                try {
                    $cleanUrl = $link->getModuleLink($moduleName, $moduleController, $paramsToPass, $id_lang);
                } catch (\Exception $e) {
                    $cleanUrl = '';
                }
            }
        }

        // fallback generico
        if (empty($cleanUrl)) {
            $cleanUrl = $link->getPageLink($controllerName ?: 'index', true, $id_lang);
        }

        $this->context->smarty->assign([
            'cleanshare_clean_url' => $cleanUrl,
            'cleanshare_button_text' => $this->l('Share this page'),
        ]);

        return $this->display(__FILE__, 'views/templates/hook/cleanshare_float.tpl');
    }

    public function hookDisplayProductAdditionalInfo($params)
    {
        $pos = Configuration::get(self::CONF_BUTTON_POSITION);
        if ($pos === 'displayProductAdditionalInfo') {
            return $this->shareLink($params);
        }
        return '';
    }

    public function hookShowProductCustomized($params)
    {
        $pos = Configuration::get(self::CONF_BUTTON_POSITION);
        if ($pos === 'showProductCustomized') {
            return $this->shareLink($params);
        }
        return '';
    }

    public function hookDisplayHeader($params)
    {
        // tpl che esporta le traduzioni JS
        $translationsTpl = $this->display(__FILE__, 'views/templates/hook/cleanshare_page.tpl');

        // verifica se il float è abilitato
        $floatEnabled = Configuration::get(self::CONF_FLOAT_ENABLED) === '1';

        // assegna variabili per il template stile del flottante
        $this->context->smarty->assign([
            'float_enabled'   => $floatEnabled,
            'float_position' => Configuration::get(self::CONF_FLOAT_POSITION),
            'float_bottom' => (int) Configuration::get(self::CONF_FLOAT_BOTTOM),
            'float_zindex' => (int) Configuration::get(self::CONF_FLOAT_ZINDEX),
            'float_bg' => Configuration::get(self::CONF_FLOAT_BG),
            'float_text' => Configuration::get(self::CONF_FLOAT_TEXT),
            'float_bg_hover' => Configuration::get(self::CONF_FLOAT_BG_HOVER),
            'float_text_hover' => Configuration::get(self::CONF_FLOAT_TEXT_HOVER),
        ]);

        // style tpl (genera lo style inline in base alla config)
        $styleTpl = $this->display(__FILE__, 'views/templates/hook/cleanshare_float_style.tpl');

        // output del pulsante/float SOLO se attivo
        $floatOutput = $floatEnabled ? $this->shareLinkFloat($params) : '';

        return $translationsTpl . $styleTpl . $floatOutput;
    }
}
