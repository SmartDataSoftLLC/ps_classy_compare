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

class Classy_Whitelist_MyIP extends Module
{

    public function __construct()
    {
        $this->name = 'classy_whitelist_myip';
        $this->author = 'ClassyDevs';
        $this->version = '1.0.0';

        $this->bootstrap = true;
        parent::__construct();
        $this->templateFile = 'module:classy_whitelist_myip/views/templates/front/classy_whitelist_myip.tpl';

        $this->displayName = $this->getTranslator()->trans('Classy White List My Ip', array(), 'Modules.Contactinfo.Admin');
        $this->description = $this->getTranslator()->trans('This module will allow to site owner to white list , the ip for some people.', array(), 'Modules.Contactinfo.Admin');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return (parent::install() &&  $this->registerHook('displayMaintenance'));
    }
    public function hookDisplayMaintenance($params)
    {

        $is_whaitelist = Tools::getValue("my_ip");


        if ($is_whaitelist == 1) {

            $max_ips = 4;
            if ($max_ips == 5) {

                $message = "Sorry max no of allowed ip is white listed.";

                $this->smarty->assign(array('whitelist_message' => $message));

                echo   $this->fetch($this->templateFile);
                die();
            }

            $allowed_ips = array_map('trim', explode(',', Configuration::get('PS_MAINTENANCE_IP')));

            $new_ip = Tools::getRemoteAddr(); //'42.24.4.2';
            array_push($allowed_ips, $new_ip);

            $allow_ips_text =  implode(",", $allowed_ips);
            Configuration::updateValue('PS_MAINTENANCE_IP', $allow_ips_text);

            Tools::redirect('index.php');
            return  true;
        }
    }


    public function getContent()
    {
        $output = [];

        if (Tools::isSubmit('submitContactInfo')) {
            Configuration::updateValue('CLASSY_ALLOW_WHITE_LIST', (int)Tools::getValue('CLASSY_ALLOW_WHITE_LIST'));
           
            Configuration::updateValue('CLASSY_WHITE_LIST_MAX', (int)Tools::getValue('CLASSY_WHITE_LIST_MAX'));
            
            foreach ($this->templates as $template) {
                $this->_clearCache($template);
            }

            $output[] = $this->displayConfirmation($this->trans('Settings updated.', array(), 'Admin.Notifications.Success'));

            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true) . '&conf=6&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name);
        }

        $helper = new HelperForm();
        $helper->submit_action = 'submitContactInfo';

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans('Ip White List Settings', array(), 'Admin.Global'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Max White List User', array(), 'Modules.Classy_Hellobar.Admin'),
                        'name' => 'CLASSY_WHITE_LIST_MAX',
                        'desc' => $this->trans('Max No of users you want to white list.', array(), 'Modules.Classy_Hellobar.Admin')
                    ),

                    array(
                        'type' => 'switch',
                        'label' => $this->trans('White List User Ip', array(), 'Admin.Actions'),
                        'name' => 'CLASSY_ALLOW_WHITE_LIST',
                        'desc' => $this->trans('This will enable the ip white list features', array(), 'Modules.Contactinfo.Admin'),
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
                            ),
                ),
                'submit' => array(
                    'title' => $this->trans('Save', array(), 'Admin.Actions')
                )
            ),
        );


       

        $helper->fields_value['CLASSY_ALLOW_WHITE_LIST'] = Configuration::get('CLASSY_ALLOW_WHITE_LIST');
        $helper->fields_value['CLASSY_WHITE_LIST_MAX'] = Configuration::get('CLASSY_WHITE_LIST_MAX');
      
        $output[] =   $helper->generateForm(array($fields_form));

        return implode($output);
    }
}
