<?php
$start_import = microtime(true);

$filename = 'test.sql';
$mysql_host = 'db';
$mysql_username = 'solomono';
$mysql_password = 'VeryStrongPassword';
$mysql_database = 'solomono2';

$mysqli = new mysqli($mysql_host, $mysql_username, $mysql_password, $mysql_database);

$templine = '';
$lines = file($filename);

foreach ($lines as $line) {
// комментарии
    if (substr($line, 0, 2) == '--' || $line == '') {
        continue;
    }

// не комментарии
    $templine .= $line;
// если в конце строки точка с запятой - это конец запроса.
    if (substr(trim($line), -1, 1) == ';') {
        // запись в базу
        $mysqli->query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . $mysqli->error . '<br /><br />');
        // обнуление переменной
        $templine = '';
    }
}
echo "Данные импортированы за " . round(microtime(true) - $start_import, 4) . ' сек.';
echo "<br>";
echo "<hr>";
$start_building = microtime(true);
$cats = [];

$result = $mysqli->query("SELECT * FROM categories");

while ($row = $result->fetch_assoc()) {
    $cats[$row["categories_id"]] = [
        'categories_id' => $row["categories_id"],
        'parent_id' => $row["parent_id"]
    ];
}

$tree = buildTree($cats);

function buildTree(array &$elements, $parentId = 0)
{

    $branch = array();

    foreach ($elements as &$element) {

        if ($element['parent_id'] == $parentId) {
            $children = buildTree($elements, $element['categories_id']);
            if ($children) {
                $branch[$element['categories_id']] = buildTree($elements, $element['categories_id']);
            } else {
                $branch[$element['categories_id']] = $element['categories_id'];
            }
            unset($element);
        }
    }
    return $branch;
}

echo "<pre>";
print_r($tree);
echo "</pre>";
echo "Дерево построено за " . round(microtime(true) - $start_building, 4) . ' сек.';
echo "<br>";
echo "<hr>";

?>