<?php

declare(strict_types=1);

namespace App\Tests\Scene;

use App\Component\PlayerController;
use App\Scene\MainScene;
use PHPUnit\Framework\TestCase;
use PHPolygon\Component\BoxCollider2D;
use PHPolygon\Component\Camera2DComponent;
use PHPolygon\Component\RigidBody2D;
use PHPolygon\Component\SpriteRenderer;
use PHPolygon\Component\Transform2D;
use PHPolygon\ECS\World;
use PHPolygon\Scene\SceneBuilder;

class MainSceneTest extends TestCase
{
    public function testSceneMaterializesExpectedEntities(): void
    {
        $world = new World();
        $scene = new MainScene();
        $builder = new SceneBuilder();
        $scene->build($builder);
        $entityMap = $builder->materialize($world);

        // Camera exists
        $this->assertArrayHasKey('Camera', $entityMap);
        $this->assertTrue($world->hasComponent($entityMap['Camera'], Camera2DComponent::class));

        // Player exists with correct components
        $this->assertArrayHasKey('Player', $entityMap);
        $playerId = $entityMap['Player'];
        $this->assertTrue($world->hasComponent($playerId, Transform2D::class));
        $this->assertTrue($world->hasComponent($playerId, SpriteRenderer::class));
        $this->assertTrue($world->hasComponent($playerId, RigidBody2D::class));
        $this->assertTrue($world->hasComponent($playerId, PlayerController::class));
        $this->assertTrue($world->hasComponent($playerId, BoxCollider2D::class));
    }

    public function testSceneHasWalls(): void
    {
        $world = new World();
        $scene = new MainScene();
        $builder = new SceneBuilder();
        $scene->build($builder);
        $entityMap = $builder->materialize($world);

        for ($i = 0; $i < 4; $i++) {
            $this->assertArrayHasKey("Wall_{$i}", $entityMap);
            $wallId = $entityMap["Wall_{$i}"];
            $this->assertTrue($world->hasComponent($wallId, BoxCollider2D::class));

            $rb = $world->getComponent($wallId, RigidBody2D::class);
            $this->assertTrue($rb->isKinematic, "Wall_{$i} should be kinematic");
        }
    }

    public function testSceneHasObstacles(): void
    {
        $world = new World();
        $scene = new MainScene();
        $builder = new SceneBuilder();
        $scene->build($builder);
        $entityMap = $builder->materialize($world);

        for ($i = 0; $i < 4; $i++) {
            $this->assertArrayHasKey("Obstacle_{$i}", $entityMap);
        }
    }

    public function testSceneConfigHasZeroGravity(): void
    {
        $scene = new MainScene();
        $config = $scene->getConfig();
        $this->assertEqualsWithDelta(0.0, $config->gravity->x, 0.001);
        $this->assertEqualsWithDelta(0.0, $config->gravity->y, 0.001);
    }
}
