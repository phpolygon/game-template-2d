<?php

declare(strict_types=1);

namespace App\System;

use App\Component\Platform;
use App\Component\PlayerController;
use PHPolygon\Component\BoxCollider2D;
use PHPolygon\Component\RigidBody2D;
use PHPolygon\Component\Transform2D;
use PHPolygon\ECS\AbstractSystem;
use PHPolygon\ECS\World;
use PHPolygon\Math\Vec2;
use PHPolygon\Runtime\InputInterface;

class PlayerSystem extends AbstractSystem
{
    public function __construct(
        private readonly InputInterface $input,
    ) {}

    public function update(World $world, float $dt): void
    {
        foreach ($world->query(Transform2D::class, PlayerController::class, RigidBody2D::class) as $entity) {
            $transform = $world->getComponent($entity->id, Transform2D::class);
            $controller = $world->getComponent($entity->id, PlayerController::class);
            $rb = $world->getComponent($entity->id, RigidBody2D::class);

            // Horizontal movement
            $moveX = 0.0;
            if ($this->input->isKeyDown(GLFW_KEY_A) || $this->input->isKeyDown(GLFW_KEY_LEFT)) {
                $moveX -= 1.0;
            }
            if ($this->input->isKeyDown(GLFW_KEY_D) || $this->input->isKeyDown(GLFW_KEY_RIGHT)) {
                $moveX += 1.0;
            }
            $rb->velocity = new Vec2($moveX * $controller->moveSpeed, $rb->velocity->y);

            // Ground detection — check if resting on a platform
            $controller->grounded = $this->checkGrounded($world, $entity->id, $transform);
            if ($controller->grounded) {
                $controller->jumpsRemaining = $controller->maxJumps;
            }

            // Jump (space or W or up arrow)
            $jumpPressed = $this->input->isKeyPressed(GLFW_KEY_SPACE)
                || $this->input->isKeyPressed(GLFW_KEY_W)
                || $this->input->isKeyPressed(GLFW_KEY_UP);

            if ($jumpPressed && $controller->jumpsRemaining > 0) {
                $rb->velocity = new Vec2($rb->velocity->x, -$controller->jumpForce);
                $controller->jumpsRemaining--;
            }

            // Camera follows player horizontally, slightly above
            foreach ($world->query(Transform2D::class, \PHPolygon\Component\Camera2DComponent::class) as $camEntity) {
                $camTransform = $world->getComponent($camEntity->id, Transform2D::class);
                $camTransform->position = new Vec2(
                    $transform->position->x,
                    $transform->position->y - 80.0,
                );
            }
        }
    }

    private function checkGrounded(World $world, int $playerId, Transform2D $playerTransform): bool
    {
        $playerCollider = $world->tryGetComponent($playerId, BoxCollider2D::class);
        if (!$playerCollider instanceof BoxCollider2D) {
            return false;
        }

        $playerRect = $playerCollider->getWorldRect($playerTransform->position);
        $playerBottom = $playerRect->y + $playerRect->height;

        foreach ($world->query(Transform2D::class, BoxCollider2D::class, Platform::class) as $platformEntity) {
            $platTransform = $world->getComponent($platformEntity->id, Transform2D::class);
            $platCollider = $world->getComponent($platformEntity->id, BoxCollider2D::class);
            $platRect = $platCollider->getWorldRect($platTransform->position);

            // Check if player's feet are near the top of the platform
            $platTop = $platRect->y;
            $verticalOverlap = abs($playerBottom - $platTop);
            $horizontalOverlap = min($playerRect->x + $playerRect->width, $platRect->x + $platRect->width)
                - max($playerRect->x, $platRect->x);

            if ($verticalOverlap < 8.0 && $horizontalOverlap > 2.0) {
                return true;
            }
        }

        return false;
    }
}
