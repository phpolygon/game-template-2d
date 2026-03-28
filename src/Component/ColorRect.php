<?php

declare(strict_types=1);

namespace App\Component;

use PHPolygon\ECS\AbstractComponent;
use PHPolygon\ECS\Attribute\Property;
use PHPolygon\ECS\Attribute\Serializable;
use PHPolygon\Rendering\Color;

/**
 * A colored rectangle rendered directly via the 2D renderer.
 * No texture needed — pure procedural rendering.
 */
#[Serializable]
class ColorRect extends AbstractComponent
{
    #[Property]
    public float $width;

    #[Property]
    public float $height;

    #[Property(editorHint: 'color')]
    public Color $color;

    #[Property]
    public float $cornerRadius;

    public function __construct(
        float $width = 32.0,
        float $height = 32.0,
        ?Color $color = null,
        float $cornerRadius = 0.0,
    ) {
        $this->width = $width;
        $this->height = $height;
        $this->color = $color ?? Color::white();
        $this->cornerRadius = $cornerRadius;
    }
}
