<?php
namespace phptree\models\entity;
class Folder
{
    private int $id;
    private int $parentId;//0 for root
    private string $name;
    private bool $isEmpty;

    /**
     * @param int $id
     * @param int $parentId
     * @param string $name
     * @param bool $isEmpty
     */
    public function __construct(int $id, int $parentId, string $name, bool $isEmpty)
    {
        if (strlen($name) < 1) $name = 'Новая папка';
        $this->id = $id;
        $this->parentId = $parentId;
        $this->name = $name;
        $this->isEmpty = $isEmpty;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->isEmpty;
    }
    public static function getNew(int $parentId, string $name):Folder {
        return new Folder(0, $parentId, $name, true);
    }


    public function putInto(Folder $folder) {
        $this->parentId = $folder->getId();
    }
    public function setFilled(): void
    {
        $this->isEmpty = false;
    }
}