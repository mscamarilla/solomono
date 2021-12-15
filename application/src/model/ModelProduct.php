<?php


namespace Model;

use Core\Model;

/**
 * Class ModelProduct
 * @package Model
 */
class ModelProduct extends Model
{
    /**
     * @param $filters
     * @return array
     * @throws \Exception
     */
    public function getProducts($filters)
    {
        $products = array();

        $sql = "SELECT * FROM";
        $sql .= " product p";
        if (!empty($filters['category'])) {
            $sql .= " LEFT JOIN product_to_category p2c ON(p.prod_id = p2c.product_id) ";
        }
        $sql .= " WHERE prod_id > 0"; //id > 0 is necessary in order to be able to add AND`s below

        if (!empty($filters['category'])) {
            $sql .= " AND p2c.category_id = '" . $filters['category'] . "'";
        }

        if (!empty($filters['sort'])) {
            if ($filters['sort'] == 'cheap') {
                $sql .= " ORDER BY price ASC";
            } elseif ($filters['sort'] == 'letters') {
                $sql .= " ORDER BY name ASC";
            } elseif ($filters['sort'] == 'date') {
                $sql .= " ORDER BY date DESC";
            }

        }
        $query = $this->db->query($sql);

        if ($query->num_rows) {

            foreach ($query->rows as $row) {
                $products[] = $row;
            }
        }

        return $products;
    }

    /**
     * @param $category_id
     * @return mixed
     * @throws \Exception
     */
    public function getProductsByCategory($category_id)
    {
        $sql = "SELECT count(*) as total FROM product_to_category WHERE category_id = '" . $category_id . "';";
        $query = $this->db->query($sql);
        return $query->row['total'];

    }


}