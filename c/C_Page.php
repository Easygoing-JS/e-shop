<?php

    include_once('m/M_Text.php');
    include_once('m/M_Product.php');
    include_once('m/M_Cart.php');


    class C_Page extends C_Base
    {
        protected $total;

        public function action_index()
        {
            $this->subtitle .= '::Чтение';
            $obj = new M_Text();
            $text = $obj->text_get();


            if ($text) {
                $this->content = $this->twig_templater('v_index.twig', array('text' => $text));
            } elseif (!$text) {
                $empty = 'Информация: файл для чтения пуст.';
                $this->content = $this->twig_templater('v_index.twig', array('empty' => $empty));
            }
        }

        public function action_edit()
        {
            $this->subtitle .= '::Редактирование';
            $obj = new M_Text();

            if ($this->isPost()) {
                $obj->text_set($_POST['text']);
                header('location: index.php');
                exit();
            }

            $text = $obj->text_get();

            $this->content = $this->twig_templater('v_edit.twig', array('text' => $text));
        }

        // public function action_catalog()
        // {
        //     $this->subtitle .= '::Каталог товаров';
        //
        //     $product_object = new M_Product();
        //     $catalog = $product_object->getAllProducts();
        //
        //     $this->content = $this->twig_templater('v_catalog.twig', array('catalog' => $catalog));
        //
        //     if ($this->IsPost()) {
        //         $new_basket = new M_Cart();
        //         $result = $new_basket->addProduct($this->userID, $_POST['product_id'], 1);
        //         $this->content = $this->twig_templater('v_catalog.twig', array('catalog' => $catalog, 'text' => $result));
        //     }
        // }
        //
        // public function action_product($id)
        // {
        //     $product_object = new M_Product();
        //     $product = $product_object->getProduct($id);
        //
        //     $this->subtitle .= '::' . $product['title'];
        //     $this->content = $this->twig_templater('v_product.twig', array('product' => $product));
        // }

        // public function action_cart()
        // {
        //     $this->subtitle .= '::Корзина';
        //
        //     if ($this->IsPost() && $_POST['incGood']) {
        //         $new_basket = new M_Cart();
        //         $new_basket->addProduct($this->userID, $_POST['product_id'], 1);
        //         $this->redirectSelf();
        //     } elseif ($_POST['decGood']) {
        //         $new_basket = new M_Cart();
        //         $new_basket->decProduct($this->userID, $_POST['product_id'], 1);
        //         $this->redirectSelf();
        //     } elseif ($_POST['delGood']) {
        //         $new_basket = new M_Cart();
        //         $new_basket->delProduct($this->userID, $_POST['product_id']);
        //         $this->redirectSelf();
        //     }
        //
        //     if ($this->IsPost() && $_POST['clearCart']) {
        //         $new_basket = new M_Cart();
        //         $new_basket->clearCart($this->userID);
        //         $this->redirectSelf();
        //     }
        //
        //     if ($this->IsPost() && $_POST['toOrder']) {
        //         $new_basket = new M_Cart();
        //         $new_basket->toOrder($this->userID, $_POST['adr']);
        //     }
        //
        //     $basket_object = new M_Cart();
        //     $basket = $basket_object->getBasket($this->userID);
        //     $cartTotal = $basket_object->getTotal($this->userID);
        //
        //     $this->content = $this->twig_templater('v_cart.twig', array('products' => $basket,
        //                                                                 'total'    => $cartTotal));
        // }

        // public function action_order()
        // {
        //     $this->subtitle .= '::Подтверждение заказа';
        //     $basket_object = new M_Cart();
        //     $basket = $basket_object->getBasket($this->userID);
        //
        //     foreach ($basket as $item) {
        //         $this->total += $item['price'] * $item['count'];
        //     }
        //
        //     $this->content = $this->twig_templater('v_orders.twig', array('products' => $basket,
        //                                                                  'total'    => $this->total));
        // }
    }