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
        if (Tools::getValue('add_compare') && $this->ajax) {
            $id_product = Tools::getValue('id_product');
            if($this->context->cookie->__isset('compare_product')){
                $id_product_json = $this->context->cookie->__get('compare_product');
                $id_product_json = stripslashes($id_product_json);    // string is stored with escape double quotes 
                $id_product_json = json_decode($id_product_json, true);
                $limit = Configuration::get('CLCOMPARE_MAX', '6');
                if(count($id_product_json) >= $limit){
                    $msg = $this->trans('You can not add more than '.$limit.' products to comparison', [], 'Modules.Classycompare.Shop');
                    $result = json_encode(array('success' => 0,'count'=>count(  $id_product_json), 'msg' =>$msg ));
                    die( $result);
                }
                if (!in_array($id_product, $id_product_json)){
                    $id_product_json[] = $id_product;
                }

                
                $product_ids_json = json_encode($id_product_json, true ); 

                $this->context->cookie->__set('compare_product',  $product_ids_json);
                $this->context->cookie->write();
                $msg = $this->trans('Product Successfully Added to Comparison', [], 'Modules.Classycompare.Shop');
                $result = json_encode(array('success' => 1,'count'=>count(  $id_product_json), 'msg' =>$msg));
                die( $result);

            }else{
                $product_ids[] =  $id_product;
                $product_ids_json = json_encode($product_ids, true ); 
                $this->context->cookie->__set('compare_product',  $product_ids_json);
                $this->context->cookie->write();
                $msg = $this->trans('Product Successfully Added to Comparison', [], 'Modules.Classycompare.Shop');
                $result = json_encode(array('success' => 1,'count'=>count(  $product_ids), 'msg' =>$msg));
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
            
            $product_ids_json = json_encode($id_product_json, true ); 
            $this->context->cookie->__set('compare_product',  $product_ids_json);
            $this->context->cookie->write();

            $msg = $this->trans('Product Successfully Added to Comparison', [], 'Modules.Classycompare.Shop');
            $result = json_encode(array('success' => 1,'count'=>count(  $id_product_json), 'msg' =>$msg));
            die( $result);
        }
        die();
    }

    /**
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        parent::initContent();
    }
}
