<?php

declare(strict_types=1);

namespace App\System;

use App\Component\ColorRect;
use PHPolygon\Component\Transform2D;
use PHPolygon\ECS\AbstractSystem;
use PHPolygon\ECS\World;
use PHPolygon\Rendering\Camera2D;
use PHPolygon\Rendering\Renderer2DInterface;

/**
 * Renders ColorRect components as colored rectangles.
 * No textures needed — pure procedural 2D rendering.
 */
class PlatformerRenderSystem extends AbstractSystem
{
    public function __construct(
        private readonly Renderer2DInterface $renderer,
        private readonly Camera2D $camera,
    ) {}

    public function render(World $world): void
    {
        $this->renderer->pushTransform($this->camera->getViewMatrix());

        foreach ($world->query(Transform2D::class, ColorRect::class) as $entity) {
            $transform = $world->getComponent($entity->id, Transform2D::class);
            $rect = $world->getComponent($entity->id, ColorRect::class);

            $x = $transform->position->x - $rect->width * 0.5;
            $y = $transform->position->y - $rect->height * 0.5;

            if ($rect->cornerRadius > 0) {
                $this->renderer->drawRoundedRect(
                    $x, $y,
                    $rect->width, $rect->height,
                    $rect->cornerRadius,
                    $rect->color,
                );
            } else {
                $this->renderer->drawRect(
                    $x, $y,
                    $rect->width, $rect->height,
                    $rect->color,
                );
            }
        }

        $this->renderer->popTransform();
    }
}
