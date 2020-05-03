<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    const UNIT_KG = 'Kg';
    const UNIT_T = 'T';
    const UNIT_GR = 'gr';

    const AVAILABLE_UNITS = [
        self::UNIT_GR,
        self::UNIT_T,
        self::UNIT_KG,
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $barcode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $unit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBarcode(): ?int
    {
        return $this->barcode;
    }

    public function setBarcode(?int $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): self
    {
        if (!in_array($unit, self::AVAILABLE_UNITS)) {
            throw new InvalidArgumentException("Invalid unit");
        }

        $this->unit = $unit;

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'bar_code' => $this->getBarcode(),
            'unit' => $this->getUnit(),
        ];
    }
}
