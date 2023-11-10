<?php
namespace phptree\models\entity;
class File
{
    private int $id;
    private int $parentId;//0 for root
    private string $name;
    private bool $isLink;
    private string $content;

    /**
     * @param int $id
     * @param int $parentId
     * @param string $name
     * @param bool $isLink
     * @param string $content
     */
    public function __construct(int $id, int $parentId, string $name, bool $isLink, string $content)
    {
        $this->id = $id;
        $this->parentId = $parentId;
        $this->name = $name;
        $this->isLink = $isLink;
        $this->content = $content;
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
    public function isLink(): bool
    {
        return $this->isLink;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }


    public static function getNew(int $parentId, string $name, bool $isLink, string $content) {
        return new File(0, $parentId, $name, $isLink, $content);
    }

    public function putInto(Folder $folder) {
        $this->parentId = $folder->getId();
    }
}