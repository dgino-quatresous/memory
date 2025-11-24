<?php
namespace App\Models;

/**
 * Représente une carte du jeu Memory
 */
class Card
{
    public ?int $id = null;
    public string $value;
    public string $image = '';
    public bool $matched = false;

    public function __construct(string $value, string $image = '')
    {
        $this->value = $value;
        $this->image = $image;
    }
}
