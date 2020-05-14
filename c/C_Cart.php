<?php

    // include_once('m/M_Product.php');
    include_once('m/M_Cart.php');


    class C_Cart extends C_Base
    {
        protected $total;

        public function action_cart()
        {
            $this->subtitle .= '::Корзина';

            if ($this->IsPost() && $_POST['incGood']) {
                $new_basket = new M_Cart();
                $new_basket->addProduct($this->userID, $_POST['product_id'], 1);
                $this->redirectSelf();
            } elseif ($_POST['decGood']) {
                $new_basket = new M_Cart();
                $new_basket->decProduct($this->userID, $_POST['product_id'], 1);
                $this->redirectSelf();
            } elseif ($_POST['delGood']) {
                $new_basket = new M_Cart();
                $new_basket->delProduct($this->userID, $_POST['product_id']);
                $this->redirectSelf();
            }

            if ($this->IsPost() && $_POST['clearCart']) {
                $new_basket = new M_Cart();
                $new_basket->clearCart($this->userID);
                $this->redirectSelf();
            }

            if ($this->IsPost() && $_POST['toOrder']) {
                $new_basket = new M_Cart();
                $new_basket->toOrder($this->userID, $_POST['adr']);
            }

            $basket_object = new M_Cart();
            $basket = $basket_object->getBasket($this->userID);
            $totalPrice = $basket_object->getTotalPrice($this->userID);
            $totalCount = $basket_object->getTotalCount($this->userID);

            $this->content = $this->twig_templater('v_cart.twig', array('products'   => $basket,
                                                                        'totalPrice' => $totalPrice,
                                                                        'totalCount' => $totalCount));
        }
    }