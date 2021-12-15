<?php


namespace Model;


use Core\Model;

/**
 * Class ModelIndex
 * @package Model
 */
class ModelIndex extends Model
{
    /**
     * Check if tables are exist
     * @return bool
     * @throws \Exception
     */
    public function checkTables(): bool
    {
        $sql = "SHOW TABLES LIKE 'category'";
        $query = $this->db->query($sql);

        if ($query->num_rows) {
            return true;
        } else {
            $this->createTables();
            return false;
        }

    }

    /**
     * Create tables
     * @throws \Exception
     */
    public function createTables()
    {
        $sql = [];
        $sql[] = "CREATE TABLE category (`cat_id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(255), PRIMARY KEY (`cat_id`));";
        $sql[] = "CREATE TABLE product (`prod_id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(255), `date` date, `price` float, PRIMARY KEY (`prod_id`));";
        $sql[] = "CREATE TABLE product_to_category (product_id int(11) NOT NULL, category_id int(11)  NOT NULL, PRIMARY KEY (product_id, category_id ), FOREIGN KEY (product_id) REFERENCES product(prod_id), FOREIGN KEY (category_id) REFERENCES category(cat_id))";

        foreach ($sql as $query) {
            $this->db->query($query);
        }
    }

    /**
     * Import from file to DB
     * @param array $products
     * @throws \Exception
     */
    public function import(array $products)
    {
        foreach ($products as $product) {
            /**
             * создание товаров
             */
            $this->db->query("INSERT INTO product(name, date, price) VALUES ('" . $product['name'] . "', '" . $product['date'] . "', '" . $product['price'] . "');");
            $product_id = $this->db->insert_id();

            /*
             * создание категорий
            */
            $categories = explode(',', $product['category']);

            foreach ($categories as $category_name) {
                //существует ли такая категория
                $query = $this->db->query("SELECT cat_id FROM category WHERE name = '" . $category_name . "';");

                //если категория есть, то ее id берется из запроса
                if ($query->num_rows) {
                    $category_id = $query->row['cat_id'];
                } else {
                    //если категории нет, то создаем ее и возвращаем id
                    $this->db->query("INSERT INTO category(name) VALUES ('" . $category_name . "');");
                    $category_id = $this->db->insert_id();
                }

                /**
                 * создание связки продукт/категория
                 */

                $this->db->query("INSERT INTO product_to_category(product_id, category_id) VALUES ('" . $product_id . "', '" . $category_id . "');");
            }


        }
    }
}
