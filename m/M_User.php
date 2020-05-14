<?php

    require_once(dirname(__DIR__) . '/config/db.config.php');
    // include_once('SQL.php');
    include_once('M_PDO.php');

    class M_User /*extends M_PDO*/
    {
        // private $dbdriver = DB_DRIVER;
        // private $dbHost = DB_HOST;
        // private $dbName = DB_NAME;
        // private $dbUser = DB_USER;
        // private $dbPass = DB_PASS;
        // protected $dbh;
        protected $note;

        /*
         * РЕГИСТРАЦИЯ нового пользователя
         */
        function regnew()
        {
            $login = $_POST['login'];
            $pass = $_POST['password'];
            $name = $_POST['name'];
            $phone = $_POST['phone'];

            M_PDO::instance();

            $sql = "SELECT * FROM user WHERE login = :login";
            $params = [
                ':login' => $login
            ];

            $result = M_PDO::Select($sql, $params);

            if (!$result) {
                $sql = "INSERT INTO user(`login`, `password`, `name`, `phone`) VALUES (:login, :pass, :name, :phone)";
                $params = [
                    ':login' => $login,
                    ':pass'  => $pass,
                    ':name'  => $name,
                    ':phone' => $phone
                ];
                return M_PDO::insert($sql, $params);
            } else return false;
        }

        /*
         * АВТОРИЗАЦИЯ пользователя
         */
        function auth($login, $pass)
        {
            $sql = "SELECT * from user WHERE login = :login";
            $params = [
                ':login' => $login,
            ];

            M_PDO::instance();
            $result = M_PDO::getRow($sql, $params);

            if (!empty($pass) && $pass == $result['password']) {
                $_SESSION['user'] = $result['login'];
                $_SESSION['user_id'] = $result['id'];
                $_SESSION['user_role'] = $result['id_role'];
                return true;
            } elseif (isset($_SESSION['user']) && $_SESSION['user_id'] > 0) {
                return true;
            } elseif (empty($login) || empty($pass)) {
                $this->note = 'Поля Логин и Пароль должны быть заполнены';
                return false;
            } else {
                $this->note = 'Ошибка в паре логин\пароль';
                return false;
            }
        }

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

        public function getOrders($user_id)
        {
            M_PDO::instance();

            $sql = "SELECT o.datetime_create, o.amount, o.destination, os.order_status_name 
                    FROM orders o JOIN order_status os ON o.id_order_status = os.id_order_status 
                    WHERE id_user = :user_id";

            $params = [
                ':user_id' => $user_id
            ];
            return M_PDO::select($sql, $params);
        }

        public function getUserData($user_id)
        {
            M_PDO::instance();

            $sql = "SELECT * FROM user
                             WHERE id = :user_id";

            $params = [
                ':user_id' => $user_id
            ];
            return M_PDO::select($sql, $params);
        }
    }