<?php

namespace phptree\controllers;

use phptree\models\entity\File;
use phptree\models\entity\Folder;
use phptree\models\Repository;
require_once __DIR__ . "/../models/Repository.php";
class TreeController
{
    private Repository $repository;

    /**
     * @param Repository $repository
     */
    public function __construct()
    {
        $full = file_get_contents(__DIR__ . "/../credentials.txt");
        $user = self::getUsername($full);
        $password = self::getPassword($full);

        $this->repository = new Repository($user, $password);
    }

    function handleIndexRequest()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $this->handleGet();
    }
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $this->handlePost();
    }
    else echo 'ERROR 404: not found';



}

function handleGet(): void
{
    $folders = array();
    $fr = $this->repository->getFoldersByParent(0);

    while ($folder = $fr->fetch_assoc()) {
        $folders[] = $folder;
    }
    $files = array();
    $fr = $this->repository->getFilesByParent(0);

    while ($file = $fr->fetch_assoc()) {
        $files[] = $file;
    }
    require_once __DIR__ . "/../views/templates/phptree.php";// folders and files are used there
}

function handlePost(): void
{
    if ($_REQUEST["method"] === 'create') {
        if ($_REQUEST["which"] === 'folder') {
            $folder = Folder::getNew($_REQUEST["parentId"], $_REQUEST["name"]);
            $this->repository->writeFolder($folder);
        }
        if ($_REQUEST["which"] === 'file') {
            $file = File::getNew($_REQUEST["parentId"], $_REQUEST["name"], false, "write yours content");
            $this->repository->writeFile($file);
        }
        if ($_REQUEST["parentId"] != '0') {
            $parent = $this->repository->getDirectory(intval($_REQUEST["parentId"]));
            $parent->setFilled();
            $this->repository->writeFolder($parent);
        }
    }
    elseif ($_REQUEST["method"] === 'get') {
        $folders = array();
        $parentId = intval($_REQUEST["parentId"]);
        $fr = $this->repository->getFoldersByParent($parentId);

        while ($folder = $fr->fetch_assoc()) {
            $folders[] = $folder;
        }
        $files = array();
        $filer = $this->repository->getFilesByParent($parentId);

        while ($file = $filer->fetch_assoc()) {
            $files[] = $file;
        }
        $all = array();
        $all['folders'] = $folders;
        $all['files'] = $files;
        if (empty($all)) echo '{empty}';
        echo json_encode($all);
    }
}

    /**
     * @param $full
     * @return array|string|string[]
     */
    private static function getUsername($full)
    {
        $user = array();
        preg_match('/user=(,?\w+)/', $full, $user);
        $user = str_replace('user=', '', $user[0]);
        return $user;
    }

    /**
     * @param $full
     * @return array|string|string[]
     */
    private static function getPassword($full)
    {
        $password = array();
        preg_match('/password=([\w&\/<>?.,:;*^{}\]\[]+)/', $full, $password);
        $password = str_replace('password=', '', $password[0]);
        return $password;
    }
}