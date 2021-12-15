<?php


namespace Controller;


use Core\Controller;

/**
 * Class ControllerIndex
 * @package Controller
 */
class ControllerIndex extends Controller
{
    /**
     * Main page controller
     */
    public function actionIndex()
    {
        $this->loadModel('model_index');
        $this->loadModel('model_category');
        $this->loadModel('model_product');

        $currentUrl = 'index.php?route=index/index';
        $data['filterCategory'] = '';
        $data['filterSort'] = '';

        $filters = array();

        if (isset($this->get['category'])) {
            $filters['category'] = $this->get['category'];
            $currentUrl .= '&category=' . $this->get['category'];
            $data['filterCategory'] = $this->get['category'];
        }

        if (isset($this->get['sort'])) {
            $filters['sort'] = $this->get['sort'];
            $currentUrl .= '&sort=' . $this->get['sort'];
            $data['filterSort'] = $this->get['sort'];
        }


        $language = $this->loadLanguage('language_index');
        $isTablesExist = $this->model_index->checkTables();

        if (!$isTablesExist) {
            $this->generate();
        }
        $data['text_categories'] = $language['text_categories'];
        $data['text_date'] = $language['text_date'];
        $data['text_buy'] = $language['text_buy'];
        $data['text_full_product'] = $language['text_full_product'];
        $data['text_sort'] = $language['text_sort'];

        $products = [];
        $data_products = $this->model_product->getProducts($filters);

        foreach ($data_products as $product) {
            $product_categories = [];
            $data_product_categories = $this->model_category->getCategoriesByProduct($product['prod_id']);

            foreach ($data_product_categories as $cat_id) {
                $product_category = $this->model_category->getCategoryById($cat_id);
                $product_categories[] = [
                    'cat_id' => $cat_id,
                    'name' => $product_category,
                ];
            }

            $products[] = [
                'prod_id' => $product['prod_id'],
                'name' => $product['name'],
                'price' => number_format($product['price'], 2, '.', ''),
                'date' => $product['date'],
                'categories' => $product_categories,
            ];
        }

        $categories = [];
        $data_categories = $this->model_category->getCategories();

        foreach ($data_categories as $category) {
            $categories[] = [
                'cat_id' => $category['cat_id'],
                'name' => $category['name'],
                'products_total' => $this->model_product->getProductsByCategory($category['cat_id']),
                'value' => $currentUrl . '&category=' . $category['cat_id'],
            ];
        }

        /**
         * Сортировка
         */
        $sort = array();
        $sort[] = [
            'name' => $language['text_sort_cheap_name'],
            'text' => $language['text_sort_cheap_text'],
            'value' => $language['text_sort_cheap_name'],
        ];
        $sort[] = [
            'name' => $language['text_sort_letters_name'],
            'text' => $language['text_sort_letters_text'],
            'value' => $language['text_sort_letters_name'],
        ];
        $sort[] = [
            'name' => $language['text_sort_date_name'],
            'text' => $language['text_sort_date_text'],
            'value' => $language['text_sort_date_name'],
        ];

        $data['currentUrl'] = $currentUrl;
        $data['categories'] = $categories;
        $data['products'] = $products;
        $data['sort'] = $sort;


        $this->view->render($data);

    }

    /**
     * Ajax загрузка контента
     */
    public function actionReload()
    {
        $this->loadModel('model_product');
        $this->loadModel('model_category');

        header('Content-Type: application/json');

        $filters = array();

        if (isset($this->post['category'])) {
            $filters['category'] = $this->post['category'];
        }

        if (isset($this->post['sort'])) {
            $filters['sort'] = $this->post['sort'];
        }

        $products = [];
        $data_products = $this->model_product->getProducts($filters);

        foreach ($data_products as $product) {
            $product_categories = [];
            $data_product_categories = $this->model_category->getCategoriesByProduct($product['prod_id']);

            foreach ($data_product_categories as $cat_id) {
                $product_category = $this->model_category->getCategoryById($cat_id);
                $product_categories[] = [
                    'cat_id' => $cat_id,
                    'name' => $product_category,
                ];
            }

            $products[] = [
                'prod_id' => $product['prod_id'],
                'name' => $product['name'],
                'price' => number_format($product['price'], 2, '.', ''),
                'date' => $product['date'],
                'categories' => $product_categories,
            ];
        }

        $json = $products;

        echo json_encode($json);

    }

    /**
     * Import data to database tables
     */
    private function generate()
    {
        $data = file_get_contents('./dataset.txt');
        $rows = explode("\r\n", $data);

        $products = [];
        //заголовки столбцов
        $columns = explode(';', $rows[0]);
        //удаляем первую строку с заголовками, т.к. она не товар
        unset($rows[0]);

        //создаем массив товаров, у которого ключи - взяты с массива заголовков, а значения - с строк-товаров
        foreach ($rows as $row) {
            $product_data = explode(';', $row);
            $products[] = array_combine($columns, $product_data);
        }

        //импортируем данные
        $this->model_index->import($products);
    }

}