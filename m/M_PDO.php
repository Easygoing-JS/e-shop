<?php
    //немного доработал класс PDO
    require_once(dirname(__DIR__) . '/config/db.config.php');


    class M_PDO
    {

        // const DB_HOST = 'localhost';
        // const DB_NAME = 'test';
        // const DB_USER = 'root';
        // const DB_PASS = '';
        const DB_CHAR = 'utf8';

        protected static $instance = null;

        private function __construct() { }

        private function __clone() { }

        /**
         *
         * @return PDO
         */
        public static function instance()
        {
            if (self::$instance === null) {
                $opt = array(
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => TRUE,
                );
                $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . self::DB_CHAR;
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $opt);
            }
            return self::$instance;
        }

        /**
         *
         * @param string $sql
         * @param array $args
         * @return PDOStatement
         */
        private static function sql($sql, $args = [])
        {
            // echo "<pre>" . $sql . "</pre>";
            $stmt = self::instance()->prepare($sql);
            $stmt->execute($args);
            return $stmt;
        }

        /**
         *
         * @param string $sql
         * @param array $args
         * @return array
         */
        public static function Select($sql, $args = [])
        {
            return self::sql($sql, $args)->fetchAll();
        }

        /**
         *
         * @param string $sql
         * @param array $args
         * @return array
         */
        public static function getRow($sql, $args = [])
        {
            return self::sql($sql, $args)->fetch();
        }

        /**
         *
         * @param string $sql
         * @param array $args
         * @return integer ID
         */
        public static function insert($sql, $args = [])
        {
            self::sql($sql, $args);
            return self::instance()->lastInsertId();
        }

        /**
         *
         * @param string $sql
         * @param array $args
         * @return integer affected rows
         */
        public static function update($sql, $args = [])
        {
            $stmt = self::sql($sql, $args);
            return $stmt->rowCount();
        }

        /**
         *
         * @param string $sql
         * @param array $args
         * @return integer affected rows
         */
        public static function delete($sql, $args = [])
        {
            $stmt = self::sql($sql, $args);
            return $stmt->rowCount();
        }

    }

    /*
    db::getInstance()->Select(
                    'SELECT * FROM goods WHERE category_id = :category AND good_id=:good AND good_is_active=:status',
                    ['status' => Status::Active, 'category' => $categoryId, 'good'=>$goodId]);
    */