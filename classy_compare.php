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

        $this->displayName = $this->getTranslator()->trans('Classy Compare', array(), 'Modules.Contactinfo.Admin');
        $this->description = $this->getTranslator()->trans('Allows you to display compare.', array(), 'Modules.Contactinfo.Admin');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    }
    public function install()
    {
        Configuration::updateValue('CLCOMPARE_TEXT', 'Add to Compare');
        Configuration::updateValue('CLCOMPARE_POSITION', 'before_price');
        Configuration::updateValue('CLCOMPARE_ADDED_TEXT', 'Added to Compare');
        return parent::install()
            && $this->registerHook([
                'actionFrontControllerSetMedia',
                'displayNav2',
                'displayProductPriceBlock',
            ]);
    }
    public function hookActionFrontControllerSetMedia()
    {
        $added_text = Configuration::get('CLCOMPARE_ADDED_TEXT', 'Added to Compare');
        Media::addJsDef([
            'classy_product_compare' => $this->context->link->getModuleLink($this->name, 'addcompare', [], true),
            'classy_product_compare' => $this->context->link->getModuleLink($this->name, 'addcompare', [], true),
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
    public function hookDisplayProductPriceBlock($params){
        $position = Configuration::get('CLCOMPARE_POSITION','before_price');
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
            'compare_text' => Configuration::get('CLCOMPARE_TEXT',''),
            'addes_compare_text' => Configuration::get('CLCOMPARE_ADDED_TEXT','')
        ]);
        return $this->fetch($filePath);
    }
    public function getContent()
    {
        if (Tools::isSubmit('submitCompareSettings')) {
            Configuration::updateValue('CLCOMPARE_TEXT', Tools::getValue('CLCOMPARE_TEXT'));
            Configuration::updateValue('CLCOMPARE_POSITION', Tools::getValue('CLCOMPARE_POSITION'));
            Configuration::updateValue('CLCOMPARE_ADDED_TEXT', Tools::getValue('CLCOMPARE_ADDED_TEXT'));
            foreach ($this->templates as $template) {
                $this->_clearCache($template);
            }
            $output = $this->displayConfirmation($this->trans('Settings updated.', array(), 'Admin.Notifications.Success'));
        }
        $output = $this->compare_setting_form();
        return $output;
    }
    public function compare_setting_form(){
        $args['title'] = $this->trans(" Button Settings", array(), 'Admin.Actions');
        $field = array(
            array(
                'type'     => 'text',
                'label'    => $this->trans('Compare Text', [], 'Modules.Smartblog.Smartblog'),
                'name'     => 'CLCOMPARE_TEXT',
                'size'     => 70,
                'required' => false
            ),
            array(
                'type'     => 'text',
                'label'    => $this->trans('Added to Compare Text', [], 'Modules.Smartblog.Smartblog'),
                'name'     => 'CLCOMPARE_ADDED_TEXT',
                'size'     => 70,
                'required' => false
            ),
            array(
                'type' => 'select',
                'label' => $this->trans('Button Position', [], 'Modules.Smartblog.Smartblog'),
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
            )
        );
        $args['field'] = $field;
        $args['submit_action'] = 'submitCompareSettings';
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
                        'title' => $this->displayName . $title
                    ),
                    'input' => $field,
                    'submit' => array(
                        'title' => $this->trans('Save', array(), 'Admin.Actions')
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