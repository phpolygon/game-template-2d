<?php

declare(strict_types=1);

namespace App\Tests\Scene;

use App\Component\ColorRect;
use App\Component\Platform;
use App\Component\PlayerController;
use App\Scene\MainScene;
use PHPUnit\Framework\TestCase;
use PHPolygon\Component\BoxCollider2D;
use PHPolygon\Component\Camera2DComponent;
use PHPolygon\Component\RigidBody2D;
use PHPolygon\Component\Transform2D;
use PHPolygon\ECS\World;
use PHPolygon\Scene\SceneBuilder;

class MainSceneTest extends TestCase
{
    public function testSceneHasPlayerWithPlatformerComponents(): void
    {
        $world = new World();
        $builder = new SceneBuilder();
        (new MainScene())->build($builder);
        $map = $builder->materialize($world);

        $this->assertArrayHasKey('Player', $map);
        $id = $map['Player'];
        $this->assertTrue($world->hasComponent($id, Transform2D::class));
        $this->assertTrue($world->hasComponent($id, ColorRect::class));
        $this->assertTrue($world->hasComponent($id, BoxCollider2D::class));
        $this->assertTrue($world->hasComponent($id, RigidBody2D::class));
        $this->assertTrue($world->hasComponent($id, PlayerController::class));

        $rb = $world->getComponent($id, RigidBody2D::class);
        $this->assertTrue($rb->fixedRotation);
    }

    public function testSceneHasGround(): void
    {
        $world = new World();
        $builder = new SceneBuilder();
        (new MainScene())->build($builder);
        $map = $builder->materialize($world);

        $this->assertArrayHasKey('Ground', $map);
        $rb = $world->getComponent($map['Ground'], RigidBody2D::class);
        $this->assertTrue($rb->isKinematic);
        $this->assertTrue($world->hasComponent($map['Ground'], Platform::class));
    }

    public function testSceneHasFloatingPlatforms(): void
    {
        $world = new World();
        $builder = new SceneBuilder();
        (new MainScene())->build($builder);
        $map = $builder->materialize($world);

        $platformCount = 0;
        foreach ($map as $name => $id) {
            if (str_starts_with($name, 'Platform_')) {
                $this->assertTrue($world->hasComponent($id, Platform::class));
                $this->assertTrue($world->hasComponent($id, BoxCollider2D::class));
                $platformCount++;
            }
        }
        $this->assertGreaterThan(10, $platformCount);
    }

    public function testSceneHasCamera(): void
    {
        $world = new World();
        $builder = new SceneBuilder();
        (new MainScene())->build($builder);
        $map = $builder->materialize($world);

        $this->assertArrayHasKey('Camera', $map);
        $this->assertTrue($world->hasComponent($map['Camera'], Camera2DComponent::class));
    }

    public function testSceneGravityPointsDown(): void
    {
        $config = (new MainScene())->getConfig();
        $this->assertGreaterThan(0.0, $config->gravity->y);
    }
}
