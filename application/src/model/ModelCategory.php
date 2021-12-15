<?php


namespace Model;

use Core\Model;


/**
 * Class ModelCategory
 * @package Model
 */
class ModelCategory extends Model
{
    /**
     * @return array
     * @throws \Exception
     */
    public function getCategories()
    {
        $categories = array();

        $sql = "SELECT * FROM category";
        $query = $this->db->query($sql);

        if ($query->num_rows) {

            foreach ($query->rows as $row) {
                $categories[] = $row;
            }
        }

        return $categories;
    }

    /**
     * @param $category_id
     * @return mixed
     * @throws \Exception
     */
    public function getCategoryById($category_id)
    {
        $sql = "SELECT * FROM category WHERE cat_id = '" . $category_id . "';";
        $query = $this->db->query($sql);

        return $query->row['name'];


    }

    /**
     * @param $product_id
     * @return array
     * @throws \Exception
     */
    public function getCategoriesByProduct($product_id)
    {
        $categories = array();

        $sql = "SELECT category_id FROM product_to_category WHERE product_id = '" . $product_id . "';";
        $query = $this->db->query($sql);

        if ($query->num_rows) {

            foreach ($query->rows as $row) {
                $categories[] = $row['category_id'];
            }
        }

        return $categories;
    }

}