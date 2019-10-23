<?php
namespace AGD\Wis;

require $_SERVER['DOCUMENT_ROOT'] . '/wis-api/api/utils/db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/wis-api/api/utils/http.php';
require $_SERVER['DOCUMENT_ROOT'] . '/wis-api/api/models/product.php';


use Exception;
use PDOException;

Http::SetDefaultHeaders('GET');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    Http::ReturnError(405, array('message' => 'Request method is not allowed.'));
    return;
}

$id = 0;
$category = '';

if (array_key_exists('id', $_GET)) {
    $id = intval($_GET['id']);
}

if (array_key_exists('category', $_GET)) {
    $category = $_GET['category'];
}

try {
    if ($id === 0) {
        $db = new Db('SELECT * FROM `products`');

        if($category !== ''){
            $db = new Db('SELECT * FROM `products` WHERE category = :category');
            $db->bindParam(':category', $category);
        }

        $response = array();

        if ($db->execute() > 0) {
            $records = $db->fetchAll();
            foreach ($records as &$record) {
                $product = new Product($record);
                array_push($response, $product);
            }
        }
       
        Http::ReturnSuccess($response);
    } else {
        $db = new Db('SELECT * FROM `products` WHERE id = :id LIMIT 1');
        $db->bindParam(':id', $id);
        if ($db->execute() === 0) {
            Http::ReturnError(404, array('message' => 'Product not found.'));
        } else {
            $record = $db->fetchAll()[0];
            $product = new Product($record);
            Http::ReturnSuccess($product);
        }
    }
} catch (PDOException $pe) {
    Db::ReturnDbError($pe);
} catch (Exception $e) {
    Http::ReturnError(500, array('message' => 'Server error: ' . $e->getMessage() . '.'));
}