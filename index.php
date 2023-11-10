<?php
require_once __DIR__ . "/controllers/TreeController.php";
use phptree\controllers\TreeController;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);



try {
    $treeController = new TreeController();
    $treeController->handleIndexRequest();
} catch(Exception $e) {
    echo $e->getMessage();
}