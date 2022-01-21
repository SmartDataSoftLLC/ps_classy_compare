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
        return parent::install()
            && $this->registerHook([
                'actionFrontControllerSetMedia',
                'displayNav2',
                'displayProductPriceBlock',
            ]);
    }
    public function hookActionFrontControllerSetMedia()
    {
        Media::addJsDef([
            'classy_product_compare' => $this->context->link->getModuleLink($this->name, 'addcompare', [], true),
        ]);

        $this->context->controller->registerJavascript('modules-classycompare', 'modules/' . $this->name . '/views/js/js_classycompare.js');
    }

    //displayNav2
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

        if (  $params['type'] != 'before_price') {
            return false;
            }

            $product = $params['product'];
            $idProduct = $product['id_product'];
           // echo $idProduct;
/*
       $product = $configuration['product'];
        $idProduct = 1;//$product['id_product'];
       // $variables = $this->getWidgetVariables($hookName, ['id_product' => $idProduct]);

        $variables = array_merge($variables, [
            'product' => $product,
            'product_comment_grade_url' => $this->context->link->getModuleLink('productcomments', 'CommentGrade'),
        ]);

       /* if ( $hookName == 'displayProductPriceBlock' && $params['type'] != 'after_price') {
            return false;
            }
*/
        $filePath = 'module:classy_compare/views/templates/hook/classy-compare-button.tpl';
        $this->smarty->assign( ['id_product' => $idProduct]);

        return $this->fetch($filePath);
    }
  

 

  

    
    public function getContent()
    {
        $output = [];

        if (Tools::isSubmit('submitContactInfo')) {
            Configuration::updateValue('PS_CONTACT_INFO_DISPLAY_EMAIL', (int)Tools::getValue('PS_CONTACT_INFO_DISPLAY_EMAIL'));

            foreach ($this->templates as $template) {
                $this->_clearCache($template);
            }

            $output[] = $this->displayConfirmation($this->trans('Settings updated.', array(), 'Admin.Notifications.Success'));

            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true).'&conf=6&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name);
        }

        $helper = new HelperForm();
        $helper->submit_action = 'submitContactInfo';

        $field = array(
            'type' => 'switch',
            'label' => $this->trans('Display email address', array(), 'Admin.Actions'),
            'name' => 'PS_CONTACT_INFO_DISPLAY_EMAIL',
            'desc' => $this->trans('Your theme needs to be compatible with this feature', array(), 'Modules.Contactinfo.Admin'),
            'values' => array(
                array(
                    'id' => 'active_on',
                    'value' => 1,
                    'label' => $this->trans('Yes', array(), 'Admin.Global')
                ),
                array(
                    'id' => 'active_off',
                    'value' => 0,
                    'label' => $this->trans('No', array(), 'Admin.Global')
                )
            )
        );

        $helper->fields_value['PS_CONTACT_INFO_DISPLAY_EMAIL'] = Configuration::get('PS_CONTACT_INFO_DISPLAY_EMAIL');

        $output[] = $helper->generateForm(array(
            array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->displayName,
                        'icon' => 'icon-share'
                    ),
                    'input' => [$field],
                    'submit' => array(
                        'title' => $this->trans('Save', array(), 'Admin.Actions')
                    )
                )
            )
        ));

        return implode($output);
    }
}
