<?php

declare(strict_types=1);

namespace App\Component;

use PHPolygon\ECS\AbstractComponent;
use PHPolygon\ECS\Attribute\Property;
use PHPolygon\ECS\Attribute\Serializable;

#[Serializable]
class PlayerController extends AbstractComponent
{
    #[Property]
    public float $moveSpeed = 280.0;

    #[Property]
    public float $jumpForce = 480.0;

    #[Property]
    public bool $grounded = false;

    #[Property]
    public int $jumpsRemaining = 2;

    #[Property]
    public int $maxJumps = 2;
}
