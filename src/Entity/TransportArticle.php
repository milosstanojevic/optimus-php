<?php

namespace App\Entity;

use App\Repository\TransportArticleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransportArticleRepository::class)
 */
class TransportArticle
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
    private $transport_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $article_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $warehouse_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer")
     */
    private $destination_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $regal_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $regal_position_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransportId(): ?int
    {
        return $this->transport_id;
    }

    public function setTransportId(int $transport_id): self
    {
        $this->transport_id = $transport_id;

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

    public function getWarehouseId(): ?int
    {
        return $this->warehouse_id;
    }

    public function setWarehouseId(?int $warehouse_id): self
    {
        $this->warehouse_id = $warehouse_id;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getDestinationId(): ?int
    {
        return $this->destination_id;
    }

    public function setDestinationId(int $destination_id): self
    {
        $this->destination_id = $destination_id;

        return $this;
    }

    public function getRegalId(): ?int
    {
        return $this->regal_id;
    }

    public function setRegalId(?int $regal_id): self
    {
        $this->regal_id = $regal_id;

        return $this;
    }

    public function getRegalPositionId(): ?int
    {
        return $this->regal_position_id;
    }

    public function setRegalPositionId(?int $regal_position_id): self
    {
        $this->regal_position_id = $regal_position_id;

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'transport_id' => $this->getTransportId(),
            'article_id' => $this->getArticleId(),
            'warehouse_id' => $this->getWarehouseId(),
            'regal_id' => $this->getRegalId(),
            'regal_position_id' => $this->getRegalPositionId(),
            'destination_id' => $this->getDestinationId(),
            'quantity' => $this->getQuantity(),
        ];
    }
}
