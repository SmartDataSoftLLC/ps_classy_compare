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
            $id_product_json[] = 11;
            $id_product_json[] = 13;
            $id_product_json[] = 5;
            

            $assembler = new ProductAssembler($this->context);
            $presenterFactory = new ProductPresenterFactory($this->context);
            $presentationSettings = $presenterFactory->getPresentationSettings();
            $presenter = $presenterFactory->getPresenter();
            $products_for_template = [];
            foreach ( $id_product_json as $k => &$id) {
                $curProduct = new Product((int)$id, true, $this->context->language->id);
                $curProduct->id_product = $id;
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

            $compare_products = array();
            $list_ids = array();
            $compare_data = array();
            $compare_data_hidden = array();
            $add_cart_arrays = array();
            $product_links = array();
            foreach($products_for_template as $product){
                $list_ids[] = $product['id_product'];

                $compare_data['id'][$product['id_product']] = $product['id_product'];
                $compare_data['thumbnail'][$product['id_product']] = $product['cover']['bySize']['home_default']['url'];
                $compare_data['name'][$product['id_product']] = $product['name'];
                $compare_data['description'][$product['id_product']] = $product['description_short'];
                $compare_data['price'][$product['id_product']] = $product['price'];
                $compare_data['quantity'][$product['id_product']] = $product['quantity'] . $this->trans(' Items', [], 'Modules.Classycompare.Shop');

                foreach ($product['features'] as $feature) {
                    if(isset($compare_data[$feature['name']][$product['id_product']])){
                        $compare_data[$feature['name']][$product['id_product']] .= ', ' .$feature['value'];
                    }else{
                        $compare_data[$feature['name']][$product['id_product']] = $feature['value'];
                    }
                }
                $add_cart_arrays[$product['id_product']] = $link->getAddToCartURL( $product['id_product'], 0 );

                $compare_data_hidden['link'][$product['id_product']] = $product['link'];
                $compare_data_hidden['discounted'][$product['id_product']] = $product['has_discount'];
                $compare_data_hidden['discount_amount'][$product['id_product']] = $product['discount_percentage'];
                $compare_data_hidden['regular_price'][$product['id_product']] = $product['regular_price'];
            }
            $compare_data['add_cart'] = $add_cart_arrays;
            $this->context->smarty->assign(
                array( 
                    'compare_data' => $compare_data,
                    'list_ids' => $list_ids,
                    'compare_data_hidden' => $compare_data_hidden
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