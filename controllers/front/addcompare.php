<?php
/**
 * 2007-2020 PrestaShop.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

/**
 * @since 1.5.0
 *
 * @property Ps_Emailsubscription $module
 */
class Classy_CompareAddCompareModuleFrontController extends ModuleFrontController
{
    private $variables = [];

    /**
     * @see FrontController::postProcess()
     */
    public function postProcess()
    {
       /* print_r(Tools::getValue('id_product'));

            
        $product_ids = array(
            0 => array(1),
            1 => array(2),
            2 => array(3)
        );
*/
if (Tools::getValue('add_compare') && $this->ajax) {

    $id_product = Tools::getValue('id_product');
   // $product_ids[] =  $id_product;

    if($this->context->cookie->__isset('compare_product')){

        $id_product_json = $this->context->cookie->__get('compare_product');

        $id_product_json = stripslashes($id_product_json);    // string is stored with escape double quotes 
        $id_product_json = json_decode($id_product_json, true);
       // print_r( $id_product_json);

       if (!in_array($id_product, $id_product_json))
            {
                $id_product_json[] = $id_product;
            }
       
       //print_r( $id_product_json);
        $product_ids_json = json_encode($id_product_json, true ); 
        $this->context->cookie->__set('compare_product',  $product_ids_json);
        $this->context->cookie->write();

        $result = json_encode(array('success' => 1,'count'=>count(  $id_product_json)));
        die( $result);

    }else{
        $product_ids[] =  $id_product;
        $product_ids_json = json_encode($product_ids, true ); 
        $this->context->cookie->__set('compare_product',  $product_ids_json);
        $this->context->cookie->write();

       // echo json_encode(array('success' => 1));

        $result = json_encode(array('success' => 1,'count'=>count(  $product_ids)));
        die( $result);
    }
   
 

}else{

    $id_product = Tools::getValue('id_product');

    $id_product_json = $this->context->cookie->__get('compare_product');

    $id_product_json = stripslashes($id_product_json);    // string is stored with escape double quotes 
    $id_product_json = json_decode($id_product_json, true);
    
        
        foreach ($id_product_json as $key => $value){
            if ($value == $id_product) {
                unset($id_product_json[$key]);
            }
        }
      //  $id_product_json[] = $id_product;
     
     // print_r($id_product_json);
      $product_ids_json = json_encode($id_product_json, true ); 
      $this->context->cookie->__set('compare_product',  $product_ids_json);
      $this->context->cookie->write();

      $result = json_encode(array('success' => 1,'count'=>count(  $id_product_json)));
      die( $result);
    //remove
  //  echo "remove compare";
}

        die();
        $this->variables['value'] = Tools::getValue('email', '');
        $this->variables['msg'] = '';
        $this->variables['conditions'] = Configuration::get('NW_CONDITIONS', $this->context->language->id);

        if (Tools::getValue('add_compare') || $this->ajax) {
            $this->module->newsletterRegistration();
            if ($this->module->error) {
                $this->variables['msg'] = $this->module->error;
                $this->variables['nw_error'] = true;
            } elseif ($this->module->valid) {
                $this->variables['msg'] = $this->module->valid;
                $this->variables['nw_error'] = false;
            }

            if ($this->ajax) {
                header('Content-Type: application/json');
                $this->ajaxDie(json_encode($this->variables));
            }
        }
    }

    /**
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        parent::initContent();

        //$this->context->smarty->assign('variables', $this->variables);
       // $this->setTemplate('module:ps_emailsubscription/views/templates/front/subscription_execution.tpl');
    }
}
