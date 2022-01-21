<?php


class Classy_CompareCompareModuleFrontController extends ModuleFrontController

{
    public $ssl = false;

    public function initContent()
    {

        parent::initContent();

        //https://github.com/PrestaShop/PrestaShop-1.6/blob/1.6.1.24/controllers/front/CompareController.php
        $compare = '';

        if ($this->context->cookie->__isset('compare_product')) {

            $id_product_json = $this->context->cookie->__get('compare_product');

            $id_product_json = stripslashes($id_product_json);    // string is stored with escape double quotes 
            $id_product_json = json_decode($id_product_json, true);


            $compare_products = array();

            $listProducts = array();
            $listFeatures = array();

            $assembler = new ProductAssembler($this->context);

            $presenterFactory = new ProductPresenterFactory($this->context);
            $presentationSettings = $presenterFactory->getPresentationSettings();
            $presenter = $presenterFactory->getPresenter();

            $products_for_template = [];


            foreach ($id_product_json as $k => &$id) {


                // Load product object
                $curProduct = new Product((int)$id, true, $this->context->language->id);
                $curProduct->id_product = $id;

                // Validate product object
                if (!Validate::isLoadedObject($curProduct) || !$curProduct->active || !$curProduct->isAssociatedToShop()) {

                    unset($ids[$k]);
                    continue;
                }

                foreach ($curProduct->getFrontFeatures($this->context->language->id) as $feature) {
                    $listFeatures[$curProduct->id][$feature['id_feature']] = $feature['value'];
                }

                $cover = Product::getCover((int)$id);

                $curProduct->id_image = Tools::htmlentitiesUTF8(Product::defineProductImage(array('id_image' => $cover['id_image'], 'id_product' => $id), $this->context->language->id));
                $curProduct->allow_oosp = Product::isAvailableWhenOutOfStock($curProduct->out_of_stock);
                $listProducts[] = $curProduct;


                $products_for_template[] = $presenter->present(
                    $presentationSettings,
                    $assembler->assembleProduct((array)$curProduct),
                    $this->context->language
                );
            }

            $this->context->smarty->assign(

                array(
                    'product_features' => $listFeatures,
                    'products' => $products_for_template,
                )
            );
        }

        $template_name = 'module:classy_compare/views/templates/front/compare.tpl';
        $this->setTemplate($template_name);
    }
    public function init()
    {

        $this->page_name = 'classy_compare'; // page_name and body id
        $this->display_column_left = false; // hides left column
        $this->display_column_right = false; // hides rigth column
        parent::init();
    }
}
