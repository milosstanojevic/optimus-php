<?php

namespace App\Entity;

use App\Repository\TransportOrderArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=TransportOrderArticleRepository::class)
 */
class TransportOrderArticle
{
    use TimestampableEntity;
    use BlameableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $transport_order_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $article_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $requested_quantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $transport_quantity;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $reason;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransportOrderId(): ?int
    {
        return $this->transport_order_id;
    }

    public function setTransportOrderId(int $transport_order_id): self
    {
        $this->transport_order_id = $transport_order_id;

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

    public function getRequestedQuantity(): ?int
    {
        return $this->requested_quantity;
    }

    public function setRequestedQuantity(int $requested_quantity): self
    {
        $this->requested_quantity = $requested_quantity;

        return $this;
    }


    public function getTransportQuantity(): ?int
    {
        return $this->transport_quantity;
    }

    public function setTransportQuantity(?int $transport_quantity): self
    {
        $this->transport_quantity = $transport_quantity;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'transport_order_id' => $this->getTransportOrderId(),
            'article_id' => $this->getArticleId(),
            'requested_quantity' => $this->getRequestedQuantity(),
            'transport_quantity' => $this->getTransportQuantity(),
            'reason' => $this->getReason(),
            'created_by' => $this->getCreatedBy(),
            'updated_by' => $this->getUpdatedBy(),
            'created_at' => $this->getCreatedAt()->getTimestamp(),
            'updated_at' => $this->getUpdatedAt()->getTimestamp(),
        ];
    }
}
