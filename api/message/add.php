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
$datecreated = date('Y-m-d H:i:s');

if (is_null($input)) {
    Http::ReturnError(400, array('message' => 'Message details are empty.'));
} else {
    try {
        $db = new Db('INSERT INTO `messages` (name, email, contact, message, datecreated)
                                VALUES (:name, :email, :contact, :message, :datecreated)');
        
        $db->bindParam(':name', property_exists($input, 'name') ? $input->name : null);
        $db->bindParam(':email', property_exists($input, 'email') ? $input->email : null);
        $db->bindParam(':contact', property_exists($input, 'contact') ? $input->contact : null);
        $db->bindParam(':message', property_exists($input, 'message') ? $input->message : null);      
        $db->bindParam(':datecreated', $datecreated);
        
        $db->execute();
        $id = $db->lastInsertId();
        $db->commit();
        Http::ReturnSuccess(array('message' => 'Message created.', 'id' => $input->id));
    } catch (PDOException $pe) {
        Db::ReturnDbError($pe);
    } catch (Exception $e) {
        Http::ReturnError(500, array('message' => 'Server error: ' . $e->getMessage() . '.'));
    }
}