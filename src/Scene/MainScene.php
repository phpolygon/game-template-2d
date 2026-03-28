<?php

declare(strict_types=1);

namespace App\Scene;

use App\Component\PlayerController;
use App\Prefab\PlayerPrefab;
use PHPolygon\Component\BoxCollider2D;
use PHPolygon\Component\Camera2DComponent;
use PHPolygon\Component\RigidBody2D;
use PHPolygon\Component\SpriteRenderer;
use PHPolygon\Component\Transform2D;
use PHPolygon\Math\Vec2;
use PHPolygon\Rendering\Color;
use PHPolygon\Scene\Scene;
use PHPolygon\Scene\SceneBuilder;
use PHPolygon\Scene\SceneConfig;

class MainScene extends Scene
{
    public function getName(): string
    {
        return 'main';
    }

    public function getConfig(): SceneConfig
    {
        $config = new SceneConfig();
        $config->clearColor = Color::hex('#0f0f1a');
        $config->gravity = new Vec2(0.0, 0.0); // Top-down: no gravity
        return $config;
    }

    public function build(SceneBuilder $builder): void
    {
        // Camera
        $builder->entity('Camera')
            ->with(new Transform2D(position: Vec2::zero()))
            ->with(new Camera2DComponent(zoom: 1.0));

        // Player via Prefab
        $builder->prefab(PlayerPrefab::class, 'Player');

        // Walls around the play area
        $wallColor = Color::hex('#334455');
        $walls = [
            ['x' => 0, 'y' => -300, 'w' => 800, 'h' => 20],   // top
            ['x' => 0, 'y' => 300, 'w' => 800, 'h' => 20],    // bottom
            ['x' => -400, 'y' => 0, 'w' => 20, 'h' => 620],   // left
            ['x' => 400, 'y' => 0, 'w' => 20, 'h' => 620],    // right
        ];

        foreach ($walls as $i => $wall) {
            $builder->entity("Wall_{$i}")
                ->with(new Transform2D(position: new Vec2((float) $wall['x'], (float) $wall['y'])))
                ->with(new SpriteRenderer(
                    width: (float) $wall['w'],
                    height: (float) $wall['h'],
                    color: $wallColor,
                ))
                ->with(new BoxCollider2D(
                    size: new Vec2((float) $wall['w'], (float) $wall['h']),
                ))
                ->with(new RigidBody2D(isKinematic: true));
        }

        // Obstacles
        $obstacleColor = Color::hex('#553344');
        $obstacles = [
            ['x' => -100, 'y' => -80],
            ['x' => 150, 'y' => 60],
            ['x' => -200, 'y' => 150],
            ['x' => 50, 'y' => -180],
        ];

        foreach ($obstacles as $i => $obs) {
            $builder->entity("Obstacle_{$i}")
                ->with(new Transform2D(position: new Vec2((float) $obs['x'], (float) $obs['y'])))
                ->with(new SpriteRenderer(width: 60.0, height: 60.0, color: $obstacleColor))
                ->with(new BoxCollider2D(size: new Vec2(60.0, 60.0)))
                ->with(new RigidBody2D(isKinematic: true));
        }
    }
}
