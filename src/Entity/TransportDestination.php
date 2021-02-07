<?php

namespace App\Entity;

use App\Repository\TransportDestinationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=TransportDestinationRepository::class)
 */
class TransportDestination
{
    use TimestampableEntity;
    use BlameableEntity;

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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $warehouse_id;

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

    public function getWarehouseId(): ?int
    {
        return $this->warehouse_id;
    }

    public function setWarehouseId(?int $warehouse_id): self
    {
        $this->warehouse_id = $warehouse_id;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'transport_id' => $this->getTransportId(),
            'warehouse_id' => $this->getWarehouseId(),
            'created_by' => $this->getCreatedBy(),
            'updated_by' => $this->getUpdatedBy(),
            'created_at' => $this->getCreatedAt()->getTimestamp(),
            'updated_at' => $this->getUpdatedAt()->getTimestamp(),
        ];
    }
}
