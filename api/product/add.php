<?php
namespace AGD\Wis;

require $_SERVER['DOCUMENT_ROOT'] . '/api/api/utils/db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/api/api/utils/http.php';

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
        $db = new Db('INSERT INTO `products` (category, title, description, photo)
                                VALUES (:category, :title, :description, :photo)');
        
        $db->bindParam(':category', property_exists($input, 'category') ? $input->category : null);
        $db->bindParam(':title', property_exists($input, 'title') ? $input->title : null);
        $db->bindParam(':description', property_exists($input, 'description') ? $input->description : null);
        $db->bindParam(':photo', property_exists($input, 'photo') ? $input->photo : null);
      
        $db->execute();
        $id = $db->lastInsertId();
        $db->commit();
        Http::ReturnSuccess(array('message' => 'Product created.', 'id' => $input->id));
    } catch (PDOException $pe) {
        Db::ReturnDbError($pe);
    } catch (Exception $e) {
        Http::ReturnError(500, array('message' => 'Server error: ' . $e->getMessage() . '.'));
    }
}