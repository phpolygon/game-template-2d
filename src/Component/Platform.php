<?php

declare(strict_types=1);

namespace App\Component;

use PHPolygon\ECS\AbstractComponent;
use PHPolygon\ECS\Attribute\Serializable;

/**
 * Marker component for platform entities.
 * Used by PlayerSystem for ground detection.
 */
#[Serializable]
class Platform extends AbstractComponent
{
}
