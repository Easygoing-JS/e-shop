<?php

    // include_once('SQL.php');
    include_once('M_PDO.php');

    class M_Product
    {
        public function getAllProducts()
        {
            M_PDO::instance();
            $sql = "SELECT * FROM products";
            return M_PDO::Select($sql);
        }

        public function getProduct($id)
        {
            M_PDO::instance();
            $sql = "SELECT * FROM products WHERE id = :id";
            $params = [
                ':id' => $id
            ];
            return M_PDO::getRow($sql, $params);
        }
    }

    // SELECT * FROM goods
    // WHERE category_id = :category AND good_id=:good AND good_is_active=:status',
    // ['status' => Status::Active, 'category' => $categoryId, 'good' => $goodId]);