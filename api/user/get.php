<?php
namespace AGD\Wis;

require $_SERVER['DOCUMENT_ROOT'] . '/wis-api/api/api/utils/db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/wis-api/api/api/utils/http.php';
require $_SERVER['DOCUMENT_ROOT'] . '/wis-api/api/api/models/user.php';


use Exception;
use PDOException;

Http::SetDefaultHeaders('GET');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    Http::ReturnError(405, array('message' => 'Request method is not allowed.'));
    return;
}

$username = '';
$password = '';

if (array_key_exists('username', $_GET) && array_key_exists('password', $_GET)) {
    $username = $_GET['username'];
    $password = $_GET['password'];
}

try {
        $db = new Db('SELECT * FROM `users` WHERE username = :username AND password = :password LIMIT 1');
        $db->bindParam(':username', $username);
        $db->bindParam(':password', $password);
        if ($db->execute() === 0) {
            Http::ReturnError(404, array('message' => 'User not found.'));
        } else {
            $record = $db->fetchAll()[0];
            $user = new User($record);
            Http::ReturnSuccess($user);
        }
    
} catch (PDOException $pe) {
    Db::ReturnDbError($pe);
} catch (Exception $e) {
    Http::ReturnError(500, array('message' => 'Server error: ' . $e->getMessage() . '.'));
}