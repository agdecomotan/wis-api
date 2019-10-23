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
    Http::ReturnError(400, array('message' => 'Rate details are empty.'));
} else {
    try {
        $db = new Db('INSERT INTO `rates` (description, rate, package, addon)
                                VALUES (:description, :rate, :package, :addon)');
        
        $db->bindParam(':description', property_exists($input, 'description') ? $input->description : null);
        $db->bindParam(':rate', property_exists($input, 'rate') ? $input->rate : null);
        $db->bindParam(':package', property_exists($input, 'package') ? $input->package : null);
        $db->bindParam(':addon', property_exists($input, 'addon') ? $input->addon : null);
      
        $db->execute();
        $id = $db->lastInsertId();
        $db->commit();
        Http::ReturnSuccess(array('message' => 'Rate created.', 'id' => $input->id));
    } catch (PDOException $pe) {
        Db::ReturnDbError($pe);
    } catch (Exception $e) {
        Http::ReturnError(500, array('message' => 'Server error: ' . $e->getMessage() . '.'));
    }
}