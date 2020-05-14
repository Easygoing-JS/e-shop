<?php

    // include_once('SQL.php');
    include_once('M_PDO.php');

    class M_Cart /*extends SQL*/
    {
        public $order_id, $product_id, $user_id, $count, $status;

        public function getBasket($user_id)
        {
            M_PDO::instance();
            $sql = "SELECT * FROM cart 
                             JOIN products ON (cart.product_id = products.id) 
                             WHERE user_id = :user_id AND id_order IS NULL";
            $params = [
                ':user_id' => $user_id
            ];
            return M_PDO::Select($sql, $params);
        }

        public function addProduct($user_id, $product_id, $count)
        {
            M_PDO::instance();
            $sql = "SELECT * FROM cart 
                    WHERE user_id = :user_id AND product_id = :product_id AND id_order IS NULL";
            $params = [
                ':user_id'    => $user_id,
                ':product_id' => $product_id,
            ];
            $result = M_PDO::getRow($sql, $params);

            if (!$result) {
                $sql = "INSERT INTO cart (
                                    `user_id`, 
                                    `product_id`, 
                                    `count`) 
                                VALUES (
                                    :user_id,
                                    :product_id,
                                    :count)";
                $params = [
                    ':user_id'    => $user_id,
                    ':product_id' => $product_id,
                    ':count'      => $count
                ];
                return M_PDO::Insert($sql, $params);
            } elseif ($result) {
                $sql = "UPDATE cart 
                        SET `count` = `count`+:count 
                        WHERE user_id = :user_id AND product_id = :product_id AND id_order IS NULL";
                $params = [
                    ':user_id'    => $user_id,
                    ':product_id' => $product_id,
                    'count'       => $count
                ];
            }
            return M_PDO::update($sql, $params);
        }

        public function decProduct($user_id, $product_id, $count)
        {
            M_PDO::instance();
            $sql = "SELECT * FROM cart 
                    WHERE user_id = :user_id AND product_id = :product_id AND id_order IS NULL";
            $params = [
                ':user_id'    => $user_id,
                ':product_id' => $product_id,
            ];
            $result = M_PDO::getRow($sql, $params);

            if ($result['count'] > 1) {
                $sql = "UPDATE cart 
                        SET `count` = `count`-:count 
                        WHERE user_id = :user_id AND product_id = :product_id AND id_order IS NULL";
                $params = [
                    ':user_id'    => $user_id,
                    ':product_id' => $product_id,
                    'count'       => $count
                ];
            } elseif ($result['count'] == 1) {
                $this->delProduct($user_id, $product_id);
            }
            return M_PDO::update($sql, $params);
        }

        public function delProduct($user_id, $product_id)
        {
            M_PDO::instance();
            $sql = "DELETE FROM cart WHERE user_id = :user_id AND product_id = :product_id AND id_order IS NULL";
            $params = [
                ':user_id'    => $user_id,
                ':product_id' => $product_id,
            ];
            return M_PDO::delete($sql, $params);
        }

        public function getTotalPrice($user_id)
        {
            $sql = "SELECT SUM(price * count) as 'sum' FROM products 
                    JOIN cart ON (cart.product_id = products.id) WHERE user_id = :user_id AND id_order IS NULL";
            $params = [
                ':user_id' => $user_id,
            ];
            return M_PDO::getRow($sql, $params)['sum'];
        }

        public function getTotalCount($user_id)
        {
            $sql = "SELECT SUM(count) as 'sum' FROM products
                    JOIN cart ON (cart.product_id = products.id) WHERE user_id = :user_id AND id_order IS NULL";
            $params = [
                ':user_id' => $user_id,
            ];
            // return M_PDO::getRow($sql, $params)['sum'];
            $result = M_PDO::getRow($sql, $params)['sum'];
            if ($result) {
                return $result;
            } elseif (!$result) {
                return 0;
            }
        }

        public function clearCart($user_id)
        {
            M_PDO::instance();
            $sql = "DELETE FROM cart where id_order IS NULL and user_id = :user_id";
            $params = [
                ':user_id' => $user_id
            ];
            return M_PDO::update($sql, $params);
        }

        public function toOrder($user_id, $destination)
        {
            $totalPrice = $this->getTotalPrice($user_id);
            $totalCount = $this->getTotalCount($user_id);
            $sql = "INSERT INTO orders (`id_user`, 
                                        `amount`, 
                                        `id_order_status`,
                                        `count`,
                                        `destination`) 
                                VALUES (:userId, 
                                        :totalPrice, 
                                        1,
                                        :totalCount, 
                                        :dest)";
            $params = ['userId'     => $user_id,
                       'totalPrice' => $totalPrice,
                       'totalCount' => $totalCount,
                       'dest'       => $destination];
            $orderId = M_PDO::insert($sql, $params);
            $sql = "UPDATE cart 
                    SET id_order = :orderId 
                    WHERE user_id = :userId AND id_order IS NULL";
            $params = ['userId'  => $user_id,
                       'orderId' => $orderId];
            M_PDO::update($sql, $params);
        }
    }