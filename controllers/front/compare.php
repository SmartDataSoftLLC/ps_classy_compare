<?php


class Classy_CompareCompareModuleFrontController extends ModuleFrontController
 
{
    public $ssl = false;

	public function initContent(){

		parent::initContent();
        $compare = '';
        $link         = new \Link();
        $context         = Context::getContext();

        // if($this->context->cookie->__isset('compare_product')){
            // $id_product_json = $this->context->cookie->__get('compare_product');
            // $id_product_json = stripslashes($id_product_json);    // string is stored with escape double quotes 
            // $id_product_json = json_decode($id_product_json, true);

            $id_product_json[] = 1;
            $id_product_json[] = 2;
            $id_product_json[] = 7;
            $compare_products = array();
            $list_ids = array();
            $compare_data = array();
            $add_cart_arrays = array();
            $product_links = array();
            foreach ( $id_product_json as $k => &$id) {
                $curProduct = new Product((int)$id, true, $this->context->language->id);
                $curProduct->id_product = $id;
                // Validate product object
                if (!Validate::isLoadedObject($curProduct) || !$curProduct->active || !$curProduct->isAssociatedToShop()) {
                    unset($ids[$k]);
                    continue;
                }
                $list_ids[] = $curProduct->id;
                $cover = Product::getCover((int)$curProduct->id);
                $compare_data['id'][$curProduct->id] = $curProduct->id;
                $compare_data['thumbnail'][$curProduct->id] = $context->link->getImageLink($curProduct->link_rewrite, $cover['id_image'], 'home_default');
                $compare_data['name'][$curProduct->id] = $curProduct->name;
                $compare_data['price'][$curProduct->id] = $curProduct->price;
                $compare_data['discounted_price'][$curProduct->id] = $curProduct->price;
                $compare_data['discount'][$curProduct->id] = Product::isDiscounted((int)$curProduct->id);
                $compare_data['specific'][$curProduct->id] = $curProduct->price;
                $compare_data['quantity'][$curProduct->id] = $curProduct->quantity . $this->trans(' Items', [], 'Modules.Classycompare.Shop');;
                $compare_data['condition'][$curProduct->id] = $curProduct->condition;
                $product_links[$curProduct->id] = $link->getProductLink($curProduct);
                foreach ($curProduct->getFrontFeatures($this->context->language->id) as $feature) {
                    if(isset($compare_data[$feature['name']][$curProduct->id])){
                        $compare_data[$feature['name']][$curProduct->id] .= ', ' .$feature['value'];
                    }else{
                        $compare_data[$feature['name']][$curProduct->id] = $feature['value'];
                    }
                }
                $add_cart_arrays[$curProduct->id] = $link->getAddToCartURL( $curProduct->id, 0 );
            }
            $compare_data['add_cart'] = $add_cart_arrays;
            $this->context->smarty->assign(
                array( 
                    'compare_data' => $compare_data,
                    'list_ids' => $list_ids,
                    'product_links' => $product_links
                ));
        // }
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