<?php
namespace phptree\models;


use Exception;
require_once __DIR__ . "/../models/entity/File.php";
use phptree\models\entity\File;
require_once __DIR__ . "/../models/entity/Folder.php";
use phptree\models\entity\Folder;
use mysqli;

class Repository
{
    private mysqli $conn;
    public function __construct(string $user, string $password)
    {
        $this->conn = new mysqli("localhost", $user, $password);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        $this->conn->query('CREATE DATABASE IF NOT EXISTS phptree;');

        $this->conn->select_db('phptree');

        $this->conn->query('CREATE TABLE IF NOT EXISTS File'."
         (
                          ID int(11) AUTO_INCREMENT PRIMARY KEY,
                          PARENT_ID int(11) NOT NULL,
                          _NAME varchar(255) NOT NULL,
                          CONTENT LONGTEXT,
                          IS_LINK int
                          );");


        $this->conn->query('CREATE TABLE IF NOT EXISTS Folder'."
         (
                          ID int(11) AUTO_INCREMENT PRIMARY KEY,
                          PARENT_ID int(11) NOT NULL,
                          _NAME varchar(255) NOT NULL,
                          IS_EMPTY int
                          );");
    }





    public function writeFolder(Folder $folder):bool {
        if ($folder->getId() === 0) {
            $name = $folder->getName();
            $parentId = $folder->getParentId();

            $str_num = $this->check_foldername($parentId, $name);
            $name.=$str_num;

            $query = "INSERT INTO Folder (PARENT_ID, _NAME, IS_EMPTY)
VALUES ('$parentId','$name', 1)";
        } else {
            $id = $folder->getId();
            $name = $folder->getName();
            $parentId = $folder->getParentId();
            $isEmpty = $folder->isEmpty() ? 1 : 0;

            $query = "UPDATE Folder
             SET PARENT_ID = $parentId, _NAME = '$name', IS_EMPTY = $isEmpty
            WHERE ID = $id;";
            echo $query;
        }
        try {
            $mysqli_result = $this->conn->query($query);
        } catch(\Throwable $e) {
            echo $e->getMessage();
        }

        return $mysqli_result;
    }



    public function writeFile(File $file):bool {
        if ($file->getId() === 0) {
            $name = $file->getName();
            $parentId = $file->getParentId();
            $isLink = $file->isLink() ? 1 : 0;
            $content = $file->getContent();

            $str_num = $this->check_filename($parentId, $name);
            $name.=$str_num;

            $query = "INSERT INTO File (PARENT_ID, _NAME, IS_LINK, CONTENT)
VALUES ('$parentId', '$name', $isLink, '$content')";
        } else {
            $id = $file->getId();
            $name = $file->getName();
            $parentId = $file->getParentId();
            $isLink = $file->isLink() ? 1 : 0;
            $content = $file->getContent();

            $query = "UPDATE File
            SET CONTENT = $content, PARENT_ID = $parentId, _NAME = $name, IS_LINK = $isLink
            WHERE ID = $id;";
        }
        return $this->conn->query($query);
    }
    public function getDirectory(int $id): Folder {
        $sql = "SELECT * FROM Folder WHERE ID = $id";
        $result = $this->conn->query($sql);
        $ar = $result->fetch_assoc();

        return new Folder($id, $ar['PARENT_ID'], $ar['_NAME'], $ar['IS_EMPTY']);
    }
    public function getFile(int $id): File {
        $file = new File();
        return $file;
    }

    public function getFoldersByParent(int $parentId) {
        $sql = "SELECT * FROM Folder WHERE PARENT_ID = $parentId";
        $result = $this->conn->query($sql);

        return $result;
    }
    public function getFilesByParent(int $parentId) {
        $sql = "SELECT * FROM File WHERE PARENT_ID = $parentId";
        $result = $this->conn->query($sql);

        return $result;

    }

    /**
     * @param int $parentId
     * @param string $name
     * @return string
     */
    public function check_filename(int $parentId, string $name): string
    {
        $ar = $this->getFilesByParent($parentId);
        $num = 1;
        $str_num = '';
        while ($res = $ar->fetch_assoc()) {
            if ($name . $str_num === $res["_NAME"]) {
                $str_num = ' (' . $num . ')';
                $num++;
            }
        }
        return $str_num;
    }

    /**
     * @param int $parentId
     * @param string $name
     * @return string
     */
    public function check_foldername(int $parentId, string $name): string
    {
        $ar = $this->getFoldersByParent($parentId);
        $num = 1;
        $str_num = '';
        while ($res = $ar->fetch_assoc()) {
            if ($name . $str_num === $res["_NAME"]) {
                $str_num = ' (' . $num . ')';
                $num++;
            }
        }
        return $str_num;
    }


}