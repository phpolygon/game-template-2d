<?php

declare(strict_types=1);

namespace App\Prefab;

use App\Component\PlayerController;
use PHPolygon\Component\BoxCollider2D;
use PHPolygon\Component\RigidBody2D;
use PHPolygon\Component\SpriteRenderer;
use PHPolygon\Component\Transform2D;
use PHPolygon\Math\Vec2;
use PHPolygon\Rendering\Color;
use PHPolygon\Scene\EntityDeclaration;
use PHPolygon\Scene\PrefabInterface;
use PHPolygon\Scene\SceneBuilder;

class PlayerPrefab implements PrefabInterface
{
    public static function getName(): string
    {
        return 'Player';
    }

    public function build(SceneBuilder $builder): EntityDeclaration
    {
        return $builder->entity('Player')
            ->with(new Transform2D(position: Vec2::zero()))
            ->with(new SpriteRenderer(
                width: 32.0,
                height: 32.0,
                color: Color::hex('#4488ff'),
            ))
            ->with(new BoxCollider2D(size: new Vec2(32.0, 32.0)))
            ->with(new RigidBody2D(drag: 8.0))
            ->with(new PlayerController());
    }
}
