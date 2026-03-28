<?php

declare(strict_types=1);

namespace App\System;

use App\Component\PlayerController;
use PHPolygon\Component\Camera2DComponent;
use PHPolygon\Component\RigidBody2D;
use PHPolygon\Component\Transform2D;
use PHPolygon\ECS\AbstractSystem;
use PHPolygon\ECS\World;
use PHPolygon\Math\Vec2;
use PHPolygon\Rendering\Color;
use PHPolygon\Rendering\Renderer2DInterface;
use PHPolygon\Runtime\InputInterface;

class PlayerSystem extends AbstractSystem
{
    public function __construct(
        private readonly InputInterface $input,
        private readonly Renderer2DInterface $renderer,
    ) {}

    public function update(World $world, float $dt): void
    {
        foreach ($world->query(Transform2D::class, PlayerController::class, RigidBody2D::class) as $entity) {
            $transform = $world->getComponent($entity->id, Transform2D::class);
            $controller = $world->getComponent($entity->id, PlayerController::class);
            $rb = $world->getComponent($entity->id, RigidBody2D::class);

            $moveX = 0.0;
            $moveY = 0.0;

            if ($this->input->isKeyDown(GLFW_KEY_W) || $this->input->isKeyDown(GLFW_KEY_UP)) {
                $moveY -= 1.0;
            }
            if ($this->input->isKeyDown(GLFW_KEY_S) || $this->input->isKeyDown(GLFW_KEY_DOWN)) {
                $moveY += 1.0;
            }
            if ($this->input->isKeyDown(GLFW_KEY_A) || $this->input->isKeyDown(GLFW_KEY_LEFT)) {
                $moveX -= 1.0;
            }
            if ($this->input->isKeyDown(GLFW_KEY_D) || $this->input->isKeyDown(GLFW_KEY_RIGHT)) {
                $moveX += 1.0;
            }

            // Normalize diagonal movement
            $len = sqrt($moveX * $moveX + $moveY * $moveY);
            if ($len > 0.0) {
                $moveX /= $len;
                $moveY /= $len;
            }

            $rb->velocity = new Vec2(
                $moveX * $controller->speed,
                $moveY * $controller->speed,
            );

            // Camera follows player
            foreach ($world->query(Transform2D::class, Camera2DComponent::class) as $camEntity) {
                $camTransform = $world->getComponent($camEntity->id, Transform2D::class);
                $camTransform->position = $transform->position;
            }
        }
    }

    public function render(World $world): void
    {
        foreach ($world->query(Transform2D::class, PlayerController::class) as $entity) {
            $transform = $world->getComponent($entity->id, Transform2D::class);

            $this->renderer->drawText(
                sprintf('Pos: %.0f, %.0f', $transform->position->x, $transform->position->y),
                10, 30, 14,
                Color::hex('#888899'),
            );
        }
    }
}
