<?php
namespace AGD\Wis;

require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/http.php';
require $_SERVER['DOCUMENT_ROOT'] . '/api/models/rate.php';


use Exception;
use PDOException;

Http::SetDefaultHeaders('GET');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    Http::ReturnError(405, array('message' => 'Request method is not allowed.'));
    return;
}

$id = 0;

if (array_key_exists('id', $_GET)) {
    $id = intval($_GET['id']);
}

try {
    if ($id === 0) {
        $db = new Db('SELECT * FROM `rates`');
        $response = array();

        if ($db->execute() > 0) {
            $records = $db->fetchAll();
            foreach ($records as &$record) {
                $value = new Rate($record);
                array_push($response, $value);
            }
        }
       
        Http::ReturnSuccess($response);
    } else {
        $db = new Db('SELECT * FROM `rates` WHERE id = :id LIMIT 1');
        $db->bindParam(':id', $id);
        if ($db->execute() === 0) {
            Http::ReturnError(404, array('message' => 'Rate not found.'));
        } else {
            $record = $db->fetchAll()[0];
            $value = new Rate($record);
            Http::ReturnSuccess($value);
        }
    }
} catch (PDOException $pe) {
    Db::ReturnDbError($pe);
} catch (Exception $e) {
    Http::ReturnError(500, array('message' => 'Server error: ' . $e->getMessage() . '.'));
}