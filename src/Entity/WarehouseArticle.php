<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WarehouseArticleRepository")
 */
class WarehouseArticle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $warehouse_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $article_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $regal_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $regal_position_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWarehouseId(): ?int
    {
        return $this->warehouse_id;
    }

    public function setWarehouseId(int $warehouse_id): self
    {
        $this->warehouse_id = $warehouse_id;

        return $this;
    }

    public function getArticleId(): ?int
    {
        return $this->article_id;
    }

    public function setArticleId(int $article_id): self
    {
        $this->article_id = $article_id;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getRegalId(): ?int
    {
        return $this->regal_id;
    }

    public function setRegalId(int $regalId): self
    {
        $this->regal_id = $regalId;

        return $this;
    }

    public function getRegalPositionId(): ?int
    {
        return $this->regal_position_id;
    }

    public function setRegalPositionId(int $regalPositionId): self
    {
        $this->regal_position_id = $regalPositionId;

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'warehouse_id' => $this->getWarehouseId(),
            'article_id' => $this->getArticleId(),
            'regal_id' => $this->getRegalId(),
            'regal_position_id' => $this->getRegalPositionId(),
            'quantity' => $this->getQuantity(),
        ];
    }
}
