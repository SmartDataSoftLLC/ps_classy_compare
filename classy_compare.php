<?php
/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class Classy_Compare extends Module 
{
    private $templates = array (
       
        'default' => 'classy_compare.tpl',
    );

    public function __construct()
    {
        $this->name = 'classy_compare';
        $this->author = 'ClassyDevs';
        $this->version = '1.0.0';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->getTranslator()->trans('Classy Product Comparison PrestaShop', array(), 'Modules.Classycompare.Admin');
        $this->description = $this->getTranslator()->trans('Allows your customers to compare between products before buying it. Increases sale.', array(), 'Modules.Classycompare.Admin');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    }
    public function install()
    {
        Configuration::updateValue('CLCOMPARE_TEXT', 'Add to Compare');
        Configuration::updateValue('CLCOMPARE_POSITION', 'before_price');
        Configuration::updateValue('CLCOMPARE_SINGLE_POSITION', 'product_actions');
        Configuration::updateValue('CLCOMPARE_ADDED_TEXT', 'Added to Compare');

        Configuration::updateValue('CLCOMPARE_MAX', '6');
        Configuration::updateValue('CLCOMPARE_REWRITE', 'compare');
        Configuration::updateValue('CLCOMPARE_DESCRIPTION', '1');
        Configuration::updateValue('CLCOMPARE_PRICE', '1');
        Configuration::updateValue('CLCOMPARE_CONDITION', '1');
        Configuration::updateValue('CLCOMPARE_AVAILABILITY', '1');
        Configuration::updateValue('CLCOMPARE_MANUFCTURER', '0');
        Configuration::updateValue('CLCOMPARE_SUPPLIER', '0');
        Configuration::updateValue('CLCOMPARE_FEATURES', '1');
        Configuration::updateValue('CLCOMPARE_ADDCART', '1');
        return parent::install()
            && $this->registerHook(
                [
                    'actionFrontControllerSetMedia',
                    'displayNav2',
                    'displayProductPriceBlock',
                    'displayProductActions',
                    'moduleRoutes'
                ]
            );
    }
    public function hookActionFrontControllerSetMedia()
    {
        $added_text = Configuration::get('CLCOMPARE_ADDED_TEXT', 'Added to Compare');
        $rewrite = Configuration::get('CLCOMPARE_REWRITE', 'compare');
        $compare_url = Classy_Compare::GetClassyCompareLink($rewrite);

        $link_text = $this->trans('See Comparison', [], 'Modules.Classycompare.Shop');

        Media::addJsDef([
            'classy_product_compare' => $this->context->link->getModuleLink($this->name, 'addcompare', [], true),
            'compare_url' => $compare_url,
            'link_text' => $link_text,
            'added_text' => $added_text
        ]);

        $this->context->controller->registerJavascript('modules-classycompare', 'modules/' . $this->name . '/views/js/js_classycompare.js');
        $this->context->controller->registerStylesheet('classycompare-style', 'modules/' . $this->name . '/views/css/style.css', ['media' => 'all', 'priority' => 50]);
    }
    public function hookDisplayNav2($params)
    {
        $compare_link = $this->context->link->getModuleLink('classy_compare', 'compare', [], true); 
        $compare_products_count = 0;
        if($this->context->cookie->__isset('compare_product')){
            $id_product_json = $this->context->cookie->__get('compare_product');
            $id_product_json = stripslashes($id_product_json);    // string is stored with escape double quotes 
            $id_product_json = json_decode($id_product_json, true);
            $compare_products_count = count($id_product_json);
        }
        $this->context->smarty->assign(
            array(   
                'compare_link' => $compare_link,
                'compare_products_count' => $compare_products_count 
            )
        );
        $filePath = 'module:classy_compare/views/templates/hook/classy_compare_nav.tpl';
        return $this->fetch( $filePath);
    }

    public function  hookDisplayProductActions($params){
        $product = $params['product'];
        $btn_class = 'compare-btn-single';
        $position = Configuration::get('CLCOMPARE_SINGLE_POSITION','product_actions');
        if($position != 'product_actions'){
            return;
        }
        return $this->fetch_template($product, $btn_class);
    }

    public function hookDisplayProductPriceBlock($params){

        $controller = Tools::getValue('controller');
        $position_string = '';
        $btn_class = 'compare-btn-multi';
        if($controller == 'product'){
            $position_string = 'single';
            $btn_class = 'compare-btn-single';
            $position = Configuration::get('CLCOMPARE_SINGLE_POSITION','product_actions');
            if($position == 'product_actions'){
                return;
            }
        }
        $position_string = 'CLCOMPARE_' . strtoupper($position_string) . '_POSITION';

        $position = Configuration::get($position_string,'before_price');
        if($position == 'before_price'){
            $prd = $params['product'];
            if($prd->has_discount){
                if (  $params['type'] != 'old_price') {
                    return false;
                }
            }else{
                if (  $params['type'] != 'before_price') {
                    return false;
                }
            }
        }else{
            if (  $params['type'] != 'weight') {
                return false;
            }
        }
        $product = $params['product'];
        return $this->fetch_template($product, $btn_class);
    }

    public function fetch_template($product, $class = 'compare-btn-multi'){
        $idProduct = $product['id_product'];
        $filePath = 'module:classy_compare/views/templates/hook/classy-compare-button.tpl';
        $added_already = 0;
        if($this->context->cookie->__isset('compare_product')){
            $id_product_added = $this->context->cookie->__get('compare_product');
            $id_product_added = stripslashes($id_product_added);    // string is stored with escape double quotes 
            $id_product_added = json_decode($id_product_added, true);
            if(in_array($idProduct,$id_product_added )){
                $added_already = 1;
            }
        }
        $this->smarty->assign( [
            'id_product' => $idProduct,
            'added_already' => $added_already,
            'btn_class' => $class,
            'compare_text' => Configuration::get('CLCOMPARE_TEXT',''),
            'addes_compare_text' => Configuration::get('CLCOMPARE_ADDED_TEXT','')
        ]);
        return $this->fetch($filePath);
    }

    public function hookModuleRoutes($params)
    {
        $alias = Configuration::get('CLCOMPARE_REWRITE');
        $my_link = array();
        $my_link = $this->urlPatterWithoutId($alias);
        return $my_link;
    }

    public function urlPatterWithoutId($alias)
    {
        $my_link = array(
            Configuration::get('CLCOMPARE_REWRITE') => array(
                'controller' => 'compare',
                'rule' => $alias,
                'keywords' => array(),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'classy_compare',
                )
            )
        );
        return $my_link;
    }

    public static function GetClassyCompareUrl()
    {
        $ssl_enable = Configuration::get('PS_SSL_ENABLED');
        $id_lang = (int) Context::getContext()->language->id;
        $id_shop = (int) Context::getContext()->shop->id;
        $rewrite_set = (int) Configuration::get('PS_REWRITING_SETTINGS');
        $ssl = null;
        static $force_ssl = null;
        if ($ssl === null) {
            if ($force_ssl === null)
                $force_ssl = (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'));
            $ssl = $force_ssl;
        }

        if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') && $id_shop !== null)
            $shop = new Shop($id_shop);
        else
            $shop = Context::getContext()->shop;
        $base = ($ssl == 1 && $ssl_enable == 1) ? 'https://' . $shop->domain_ssl : 'http://' . $shop->domain;
        $langUrl = Language::getIsoById($id_lang) . '/';
        if ((!$rewrite_set && in_array($id_shop, array((int) Context::getContext()->shop->id, null))) || !Language::isMultiLanguageActivated($id_shop) || !(int) Configuration::get('PS_REWRITING_SETTINGS', null, null, $id_shop))
            $langUrl = '';

        return $base . $shop->getBaseURI() . $langUrl;
    }

    public static function GetClassyCompareLink($rewrite = 'compare', $params = null, $id_shop = null, $id_lang = null)
    {

        $url = Classy_Compare::GetClassyCompareUrl();
        $dispatcher = Dispatcher::getInstance();
        $id_lang = (int) Context::getContext()->language->id;
        $force_routes = (bool) Configuration::get('PS_REWRITING_SETTINGS');
        if ($params != null) {
            return $url . $dispatcher->createUrl($rewrite, $id_lang, $params, $force_routes);
        } else {
            $params = array();
            return $url . $dispatcher->createUrl($rewrite, $id_lang, $params, $force_routes);
        }
    }

    public function getContent()
    {
        if (Tools::isSubmit('submitCompareSettings')) {
            Configuration::updateValue('CLCOMPARE_TEXT', Tools::getValue('CLCOMPARE_TEXT'));
            Configuration::updateValue('CLCOMPARE_POSITION', Tools::getValue('CLCOMPARE_POSITION'));
            Configuration::updateValue('CLCOMPARE_SINGLE_POSITION', Tools::getValue('CLCOMPARE_SINGLE_POSITION'));
            Configuration::updateValue('CLCOMPARE_ADDED_TEXT', Tools::getValue('CLCOMPARE_ADDED_TEXT'));

            if(Tools::getValue('CLCOMPARE_SINGLE_POSITION') == 'product_actions'){
                $this->registerHook('displayProductActions');
            }else{
                $this->unregisterHook('displayProductActions');
            }

            foreach ($this->templates as $template) {
                $this->_clearCache($template);
            }
            $output = $this->displayConfirmation($this->trans('Settings updated.', array(), 'Modules.Classycompare.Admin'));
        }
        if (Tools::isSubmit('submitCompareDetailsSettings')) {
            Configuration::updateValue('CLCOMPARE_MAX', Tools::getValue('CLCOMPARE_MAX'));
            Configuration::updateValue('CLCOMPARE_REWRITE', Tools::getValue('CLCOMPARE_REWRITE'));
            Configuration::updateValue('CLCOMPARE_DESCRIPTION', Tools::getValue('CLCOMPARE_DESCRIPTION'));
            Configuration::updateValue('CLCOMPARE_PRICE', Tools::getValue('CLCOMPARE_PRICE'));
            Configuration::updateValue('CLCOMPARE_CONDITION', Tools::getValue('CLCOMPARE_CONDITION'));
            Configuration::updateValue('CLCOMPARE_AVAILABILITY', Tools::getValue('CLCOMPARE_AVAILABILITY'));
            Configuration::updateValue('CLCOMPARE_MANUFCTURER', Tools::getValue('CLCOMPARE_MANUFCTURER'));
            Configuration::updateValue('CLCOMPARE_SUPPLIER', Tools::getValue('CLCOMPARE_SUPPLIER'));
            Configuration::updateValue('CLCOMPARE_FEATURES', Tools::getValue('CLCOMPARE_FEATURES'));
            Configuration::updateValue('CLCOMPARE_ADDCART', Tools::getValue('CLCOMPARE_ADDCART'));
            $output = $this->displayConfirmation($this->trans('Settings updated.', array(), 'Modules.Classycompare.Admin'));
        }
        $output = $this->license_form();
        $output .= $this->compare_setting_form();
        $output .= $this->compare_details_setting_form();
        return $output;
    }

    public function license_form(){
        $args['title'] = $this->trans("License Settings", array(), 'Modules.Classycompare.Admin');
        $field = array(
            array(
                'type'     => 'text',
                'label'    => $this->trans('Purchase Code', [], 'Modules.Classycompare.Admin'),
                'name'     => 'CLCOMPARE_PURCHASE_CODE',
                'size'     => 70,
                'required' => false
            )
        );
        $args['field'] = $field;
        $args['submit_action'] = 'submitCompareLicense';
        return $this->generate_form($args);
    }

    public function compare_setting_form(){
        $args['title'] = $this->trans("Compare Button Settings", array(), 'Modules.Classycompare.Admin');
        $field = array(
            array(
                'type'     => 'text',
                'label'    => $this->trans('Compare Text', [], 'Modules.Classycompare.Admin'),
                'name'     => 'CLCOMPARE_TEXT',
                'size'     => 70,
                'required' => false
            ),
            array(
                'type'     => 'text',
                'label'    => $this->trans('Added to Compare Text', [], 'Modules.Classycompare.Admin'),
                'name'     => 'CLCOMPARE_ADDED_TEXT',
                'size'     => 70,
                'required' => false
            ),
            array(
                'type' => 'select',
                'label' => $this->trans('Button Position', [], 'Modules.Classycompare.Admin'),
                'name' => 'CLCOMPARE_POSITION',
                'required' => false,
                'options' => array(
                    'query' => array(
                        array(
                            'id_pos' => 'before_price',
                            'name' => 'Before Price'
                        ),
                        array(
                            'id_pos' => 'after_price',
                            'name' => 'After Price'
                        )
                    ),
                    'id' => 'id_pos',
                    'name' => 'name'
                )
            ),
            array(
                'type' => 'select',
                'label' => $this->trans('Single Page Button Position', [], 'Modules.Classycompare.Admin'),
                'name' => 'CLCOMPARE_SINGLE_POSITION',
                'required' => false,
                'options' => array(
                    'query' => array(
                        array(
                            'id_pos' => 'before_price',
                            'name' => 'Before Price'
                        ),
                        array(
                            'id_pos' => 'after_price',
                            'name' => 'After Price'
                        ),
                        array(
                            'id_pos' => 'product_actions',
                            'name' => 'In Actions Block'
                        )
                    ),
                    'id' => 'id_pos',
                    'name' => 'name'
                )
            )
        );
        $args['field'] = $field;
        $args['submit_action'] = 'submitCompareSettings';
        return $this->generate_form($args);
    }

    public function compare_details_setting_form(){
        $args['title'] = $this->trans("Compare Details Settings", array(), 'Modules.Classycompare.Admin');
        $field = array(
            array(
                'type'     => 'text',
                'label'    => $this->trans('Max Number of Products in Compare', [], 'Modules.Classycompare.Admin'),
                'name'     => 'CLCOMPARE_MAX',
                'size'     => 70,
                'required' => false
            ),
            array(
                'type'     => 'text',
                'label'    => $this->trans('Details Page Url', [], 'Modules.Classycompare.Admin'),
                'name'     => 'CLCOMPARE_REWRITE',
                'size'     => 70,
                'required' => false
            ),
            array(
                'type'     => 'switch',
                'label'    => $this->trans('Show Description', [], 'Modules.Classycompare.Admin'),
                'name'     => 'CLCOMPARE_DESCRIPTION',
                'required' => false,
                'is_bool'  => true,
                'values'   => array(
                    array(
                        'id'    => 'active_on',
                        'value' => 1,
                        'label' => $this->trans('Yes', [], 'Modules.Classycompare.Admin'),
                    ),
                    array(
                        'id'    => 'active_off',
                        'value' => 0,
                        'label' => $this->trans('No', [], 'Modules.Classycompare.Admin'),
                    ),
                ),
            ),
            array(
                'type'     => 'switch',
                'label'    => $this->trans('Show Price', [], 'Modules.Classycompare.Admin'),
                'name'     => 'CLCOMPARE_PRICE',
                'required' => false,
                'is_bool'  => true,
                'values'   => array(
                    array(
                        'id'    => 'active_on',
                        'value' => 1,
                        'label' => $this->trans('Yes', [], 'Modules.Classycompare.Admin'),
                    ),
                    array(
                        'id'    => 'active_off',
                        'value' => 0,
                        'label' => $this->trans('No', [], 'Modules.Classycompare.Admin'),
                    ),
                ),
            ),
            array(
                'type'     => 'switch',
                'label'    => $this->trans('Show Condition', [], 'Modules.Classycompare.Admin'),
                'name'     => 'CLCOMPARE_CONDITION',
                'required' => false,
                'is_bool'  => true,
                'values'   => array(
                    array(
                        'id'    => 'active_on',
                        'value' => 1,
                        'label' => $this->trans('Yes', [], 'Modules.Classycompare.Admin'),
                    ),
                    array(
                        'id'    => 'active_off',
                        'value' => 0,
                        'label' => $this->trans('No', [], 'Modules.Classycompare.Admin'),
                    ),
                ),
            ),
            array(
                'type'     => 'switch',
                'label'    => $this->trans('Show Availability', [], 'Modules.Classycompare.Admin'),
                'name'     => 'CLCOMPARE_AVAILABILITY',
                'required' => false,
                'is_bool'  => true,
                'values'   => array(
                    array(
                        'id'    => 'active_on',
                        'value' => 1,
                        'label' => $this->trans('Yes', [], 'Modules.Classycompare.Admin'),
                    ),
                    array(
                        'id'    => 'active_off',
                        'value' => 0,
                        'label' => $this->trans('No', [], 'Modules.Classycompare.Admin'),
                    ),
                ),
            ),
            array(
                'type'     => 'switch',
                'label'    => $this->trans('Show Manufacturer', [], 'Modules.Classycompare.Admin'),
                'name'     => 'CLCOMPARE_MANUFCTURER',
                'required' => false,
                'is_bool'  => true,
                'values'   => array(
                    array(
                        'id'    => 'active_on',
                        'value' => 1,
                        'label' => $this->trans('Yes', [], 'Modules.Classycompare.Admin'),
                    ),
                    array(
                        'id'    => 'active_off',
                        'value' => 0,
                        'label' => $this->trans('No', [], 'Modules.Classycompare.Admin'),
                    ),
                ),
            ),
            array(
                'type'     => 'switch',
                'label'    => $this->trans('Show Supplier', [], 'Modules.Classycompare.Admin'),
                'name'     => 'CLCOMPARE_SUPPLIER',
                'required' => false,
                'is_bool'  => true,
                'values'   => array(
                    array(
                        'id'    => 'active_on',
                        'value' => 1,
                        'label' => $this->trans('Yes', [], 'Modules.Classycompare.Admin'),
                    ),
                    array(
                        'id'    => 'active_off',
                        'value' => 0,
                        'label' => $this->trans('No', [], 'Modules.Classycompare.Admin'),
                    ),
                ),
            ),
            array(
                'type'     => 'switch',
                'label'    => $this->trans('Show Features', [], 'Modules.Classycompare.Admin'),
                'name'     => 'CLCOMPARE_FEATURES',
                'required' => false,
                'is_bool'  => true,
                'values'   => array(
                    array(
                        'id'    => 'active_on',
                        'value' => 1,
                        'label' => $this->trans('Yes', [], 'Modules.Classycompare.Admin'),
                    ),
                    array(
                        'id'    => 'active_off',
                        'value' => 0,
                        'label' => $this->trans('No', [], 'Modules.Classycompare.Admin'),
                    ),
                ),
            ),
            array(
                'type'     => 'switch',
                'label'    => $this->trans('Show Add To Cart Button', [], 'Modules.Classycompare.Admin'),
                'name'     => 'CLCOMPARE_ADDCART',
                'required' => false,
                'is_bool'  => true,
                'values'   => array(
                    array(
                        'id'    => 'active_on',
                        'value' => 1,
                        'label' => $this->trans('Yes', [], 'Modules.Classycompare.Admin'),
                    ),
                    array(
                        'id'    => 'active_off',
                        'value' => 0,
                        'label' => $this->trans('No', [], 'Modules.Classycompare.Admin'),
                    ),
                ),
            )
        );
        
        $args['field'] = $field;
        $args['submit_action'] = 'submitCompareDetailsSettings';
        return $this->generate_form($args);
    }

    public function generate_form($args){
        extract($args);
        $helper = new HelperForm();
        $helper->submit_action = $submit_action;
        $helper->fields_value = $this->setConfigFildsValues($field);
        return $helper->generateForm(array(
            array(
                'form' => array(
                    'legend' => array(
                        'title' => $title
                    ),
                    'input' => $field,
                    'submit' => array(
                        'title' => $this->trans('Save', array(), 'Modules.Classycompare.Admin')
                    )
                )
            )
        ));
    }
    public function setConfigFildsValues($fields){
        $returnarr = array();
        foreach ($fields as $field) {
            $returnarr[$field['name']] = Configuration::get($field['name']);
        }
        return $returnarr;
    }
}