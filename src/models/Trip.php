<?php

class Trip{
    private $id;
    private $userId;
    private $title;
    private $description;
    private $distance;
    private $elevation;
    private $date;
    private $difficulty;
    private $maxElevation;
    private $photo;

    public function __construct(
        int $id,
        int $userId,
        string $title,
        string $description,
        float $distance,
        int $elevation,
        string $date,
        string $difficulty,
        int $maxElevation,
        string $photo = null
    )
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->title = $title;
        $this->description = $description;
        $this->distance = $distance;
        $this->elevation = $elevation;
        $this->date = $date;
        $this->difficulty = $difficulty;
        $this->maxElevation = $maxElevation;
        $this->photo = $photo;
    }

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): string { return $this->description; }
    public function getDistance(): float { return $this->distance; }
    public function getElevation(): int { return $this->elevation; }
    public function getDate(): string { return $this->date; }
    public function getDifficulty(): string { return $this->difficulty; }
    public function getMaxElevation(): int { return $this->maxElevation; }
    public function getPhoto(): ?string { return $this->photo; }
}