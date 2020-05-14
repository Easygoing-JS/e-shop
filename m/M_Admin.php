<?php

    require_once(dirname(__DIR__) . '/config/db.config.php');
    // include_once('SQL.php');
    include_once('M_PDO.php');

    class M_Admin
    {
        protected $note;

        /*
         * ВЫХОД пользователя из системы
         */
        function out()
        {
            // unset($_SESSION['user']);
            $_SESSION['user_id'] = null;
            session_destroy();
            header('Location: index.php?c=user&act=personal');
        }

        /**
         * геттер $note
         */
        public
        function getNote()
        {
            return $this->note;
        }

        public function getAllOrders()
        {
            M_PDO::instance();

            $sql = "SELECT * FROM orders 
                             JOIN order_status os ON orders.id_order_status = os.id_order_status 
                             JOIN user u ON orders.id_user = u.id
                             ORDER BY id_order DESC";
            return M_PDO::select($sql);
        }

        public function setOrderStatus($id_order, $order_status)
        {
            M_PDO::instance();

            $sql = "UPDATE orders 
                    SET id_order_status = :orderStatus 
                    WHERE id_order = :orderId";
            $params = ['orderId'     => $id_order,
                       'orderStatus' => $order_status];
            M_PDO::update($sql, $params);
        }
    }