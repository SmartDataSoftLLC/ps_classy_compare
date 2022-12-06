<?php


class Classy_CompareCompareModuleFrontController extends ModuleFrontController
 
{
    public $ssl = false;

	public function initContent(){

		parent::initContent();
        $link         = new \Link();
        $context         = Context::getContext();

        if($this->context->cookie->__isset('compare_product')){
            $id_product_json = $this->context->cookie->__get('compare_product');
            $id_product_json = stripslashes($id_product_json);    // string is stored with escape double quotes 
            $id_product_json = json_decode($id_product_json, true);
            
            if(isset($id_product_json) && !empty($id_product_json)){
                $assembler = new ProductAssembler($this->context);
                $presenterFactory = new ProductPresenterFactory($this->context);
                $presentationSettings = $presenterFactory->getPresentationSettings();
                $presenter = $presenterFactory->getPresenter();
                $products_for_template = [];

                $list_ids = array();
                $compare_data = array();
                $compare_data_hidden = array();
                $add_cart_arrays = array();
                $condition_array = array();
                $diff_array = array();

                $show_description = Configuration::get('CLCOMPARE_DESCRIPTION');
                $show_price = Configuration::get('CLCOMPARE_PRICE');
                $show_condition = Configuration::get('CLCOMPARE_CONDITION');
                $show_availability = Configuration::get('CLCOMPARE_AVAILABILITY');
                $show_features = Configuration::get('CLCOMPARE_FEATURES');
                $show_addcart = Configuration::get('CLCOMPARE_ADDCART');
                $show_manufacturer = Configuration::get('CLCOMPARE_MANUFCTURER');
                $show_supplier = Configuration::get('CLCOMPARE_SUPPLIER');

                foreach ( $id_product_json as $k => &$id) {
                    $curProduct = new Product((int)$id, true, $this->context->language->id);
                    $curProduct->id_product = $id;

                    $condition_array[$curProduct->id_product] = $curProduct->condition;
                    if(isset($condition_array)){
                        if(!in_array($curProduct->condition, $condition_array)){
                            $diff_array['condition'] = 'classy-compare-diff';
                        }
                    }
                    if(!isset($diff_array['condition'])){
                        $diff_array['condition'] = '';
                    }

                    // Validate product object
                    if (!Validate::isLoadedObject($curProduct) || !$curProduct->active || !$curProduct->isAssociatedToShop()) {
                        unset($ids[$k]);
                        continue;
                    }
                    $cover = Product::getCover((int)$id);
                    $curProduct->id_image = Tools::htmlentitiesUTF8(Product::defineProductImage(array('id_image' => $cover['id_image'], 'id_product' => $id), $this->context->language->id));
                    $curProduct->allow_oosp = Product::isAvailableWhenOutOfStock($curProduct->out_of_stock);
                    $products_for_template[] = $presenter->present(
                        $presentationSettings,
                        $assembler->assembleProduct((array)$curProduct),
                        $this->context->language
                    );
                }

                foreach($products_for_template as $product){
                    $list_ids[] = $product['id_product'];
                    $compare_data['id'][$product['id_product']] = $product['id_product'];
                    $compare_data['thumbnail'][$product['id_product']] = $product['cover']['bySize']['home_default']['url'];
                    $compare_data['name'][$product['id_product']] = $product['name'];
                    $diff_array['name'] = '';
                    if($show_description){
                        if(isset($compare_data['description'])){
                            if(!in_array($product['description_short'], $compare_data['description'])){
                                $diff_array['description'] = 'classy-compare-diff';
                            }
                        }
                        if(!isset($diff_array['description_short'])){
                            $diff_array['description'] = '';
                        }
                        $compare_data['description'][$product['id_product']] = $product['description_short'];
                        
                    }
                    if($show_price){

                        if(isset($compare_data['price'])){
                            if(!in_array($product['price'], $compare_data['price'])){
                                $diff_array['price'] = 'classy-compare-diff';
                            }
                        }
                        if(!isset($diff_array['price'])){
                            $diff_array['price'] = '';
                        }

                        $compare_data['price'][$product['id_product']] = $product['price'];

                        $compare_data_hidden['discounted'][$product['id_product']] = $product['has_discount'];
                        $compare_data_hidden['discount_amount'][$product['id_product']] = $product['discount_percentage'];
                        $compare_data_hidden['regular_price'][$product['id_product']] = $product['regular_price'];
                    }
                    if($show_condition){
                        $compare_data['condition'][$product['id_product']] = $condition_array[$product['id_product']];
                    }
                    if($show_availability){

                        if(isset($compare_data['availability'])){
                            if(!in_array($product['quantity'], $compare_data['availability'])){
                                $diff_array['availability'] = 'classy-compare-diff';
                            }
                        }
                        if(!isset($diff_array['availability'])){
                            $diff_array['availability'] = '';
                        }

                        if($product['quantity'] > 0){
                            $compare_data['availability'][$product['id_product']] = $product['quantity'] . $this->trans(' Items', [], 'Modules.Classycompare.Shop');
                        }else{
                            $compare_data['availability'][$product['id_product']] = $this->trans('Out of Stock', [], 'Modules.Classycompare.Shop');
                        }
                    }
                    if($show_manufacturer){
                        if(isset($compare_data['manufacturer'])){
                            if(!in_array($product['manufacturer_name'], $compare_data['manufacturer'])){
                                $diff_array['manufacturer'] = 'classy-compare-diff';
                            }
                        }
                        if(!isset($diff_array['manufacturer'])){
                            $diff_array['manufacturer'] = '';
                        }
                        $compare_data['manufacturer'][$product['id_product']] = $product['manufacturer_name'];
                    }
                    if($show_supplier){
                        if(isset($compare_data['supplier'])){
                            if(!in_array($product['supplier_name'], $compare_data['supplier'])){
                                $diff_array['supplier'] = 'classy-compare-diff';
                            }
                        }
                        if(!isset($diff_array['supplier'])){
                            $diff_array['supplier'] = '';
                        }
                        $compare_data['supplier'][$product['id_product']] = $product['supplier_name'];
                    }
                    if($show_features){
                        foreach ($product['features'] as $feature) {
                            if(isset($compare_data[$feature['name']])){
                                if(!in_array($feature['value'], $compare_data[$feature['name']])){
                                    $diff_array[$feature['name']] = 'classy-compare-diff';
                                }
                            }
                            if(!isset($diff_array[$feature['name']])){
                                $diff_array[$feature['name']] = '';
                            }
                            if(isset($compare_data[$feature['name']][$product['id_product']])){
                                $compare_data[$feature['name']][$product['id_product']] .= ', ' .$feature['value'];
                            }else{
                                $compare_data[$feature['name']][$product['id_product']] = $feature['value'];
                            }
                        }
                    }
                    if($show_addcart){
                        $add_cart_arrays[$product['id_product']] = $link->getAddToCartURL( $product['id_product'], 0 );
                    }

                    $compare_data_hidden['link'][$product['id_product']] = $product['link'];
                }
                if(isset($add_cart_arrays) && !empty($add_cart_arrays)){
                    $compare_data['add_cart'] = $add_cart_arrays;
                }
                $this->context->smarty->assign(
                    array( 
                        'compare_data' => $compare_data,
                        'list_ids' => $list_ids,
                        'diff_array' => $diff_array,
                        'compare_data_hidden' => $compare_data_hidden,
                        'details_background' => Configuration::get('CLCOMPARE_DETAILS_BACK_COLOR'),
                        'details_textcolor' => Configuration::get('CLCOMPARE_DETAILS_TEXT_COLOR'),
                        'with_back' => Configuration::get('CLCOMPARE_DETAILS_ROWB_COLOR'),
                        'no_back' => Configuration::get('CLCOMPARE_DETAILS_RONB_COLOR'),
                        'tborder_color' => Configuration::get('CLCOMPARE_DETAILS_TBORDER_COLOR'),
                    )
                );
            }
        }
        $template_name = 'module:classy_compare/views/templates/front/compare.tpl';
        $this->setTemplate($template_name);
    }
	public function init(){
	    $this->page_name = 'classy_compare'; // page_name and body id
	    $this->display_column_left = false; // hides left column
	    $this->display_column_right = false; // hides rigth column
	    parent::init();
	}
}