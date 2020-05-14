<?php

    include_once('m/M_Product.php');
    include_once('m/M_Cart.php');


    class C_Catalog extends C_Base
    {
        protected $total;

        public function action_catalog()
        {
            $this->subtitle .= '::Каталог товаров';

            $product_object = new M_Product();
            $catalog = $product_object->getAllProducts();

            $this->content = $this->twig_templater('v_catalog.twig', array('catalog' => $catalog));

            if ($this->IsPost()) {
                $new_basket = new M_Cart();
                $result = $new_basket->addProduct($this->userID, $_POST['product_id'], 1);
                $this->content = $this->twig_templater('v_catalog.twig', array('catalog' => $catalog, 'text' => $result));
            }
        }

        public function action_product($id)
        {
            $product_object = new M_Product();
            $product = $product_object->getProduct($id);

            $this->subtitle .= '::' . $product['title'];
            $this->content = $this->twig_templater('v_product.twig', array('product' => $product));
        }
    }