<?php
namespace AGD\Wis;

require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/http.php';

use Exception;
use PDOException;

Http::HandleOption();
Http::SetDefaultHeaders('POST');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Http::ReturnError(405, array('message' => 'Request method is not allowed.'));
    return;
}

$input = json_decode(file_get_contents("php://input"));
if (is_null($input)) {
    Http::ReturnError(400, array('message' => 'Product details are empty.'));
} else {
    try {
        $db = new Db('SELECT * FROM `products` WHERE id = :id LIMIT 1');
        $db->bindParam(':id', property_exists($input, 'id') ? $input->id : 0);

        if ($db->execute() === 0) {
            Http::ReturnError(404, array('message' => 'Product not found.'));
        } else {
            $db = new Db('DELETE FROM `products` WHERE id = :id');
            $db->bindParam(':id', property_exists($input, 'id') ? $input->id : 0);
            $db->execute();
            $db->commit();
            Http::ReturnSuccess(array('message' => 'Product deleted.', 'id' => $input->id));
        }
    } catch (PDOException $pe) {
        Db::ReturnDbError($pe);
    } catch (Exception $e) {
        Http::ReturnError(500, array('message' => 'Server error: ' . $e->getMessage() . '.'));
    }
}