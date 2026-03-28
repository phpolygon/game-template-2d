<?php

declare(strict_types=1);

namespace App\Scene;

use App\Component\ColorRect;
use App\Component\Platform;
use App\Component\PlayerController;
use PHPolygon\Component\BoxCollider2D;
use PHPolygon\Component\Camera2DComponent;
use PHPolygon\Component\RigidBody2D;
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
        $config->clearColor = Color::hex('#1a1a2e');
        $config->gravity = new Vec2(0.0, 980.0);
        return $config;
    }

    public function build(SceneBuilder $builder): void
    {
        // Camera
        $builder->entity('Camera')
            ->with(new Transform2D(position: new Vec2(0.0, -80.0)))
            ->with(new Camera2DComponent(zoom: 1.0));

        // Player — a bright blue square with rounded corners
        $builder->entity('Player')
            ->with(new Transform2D(position: new Vec2(0.0, -50.0)))
            ->with(new ColorRect(28.0, 36.0, Color::hex('#4499ff'), cornerRadius: 4.0))
            ->with(new BoxCollider2D(size: new Vec2(28.0, 36.0)))
            ->with(new RigidBody2D(fixedRotation: true))
            ->with(new PlayerController());

        // Ground — wide platform at the bottom
        $this->platform($builder, 'Ground', 0.0, 200.0, 2000.0, 40.0, '#2a4a3a');

        // Floating platforms — a fun layout to jump across
        $platforms = [
            // Starting area
            ['x' => -200, 'y' => 120, 'w' => 160, 'color' => '#3a5a4a'],
            ['x' => -50,  'y' => 40,  'w' => 120, 'color' => '#3a5a4a'],
            ['x' => 120,  'y' => -20, 'w' => 100, 'color' => '#3a5a4a'],

            // Mid section — tighter jumps
            ['x' => 280,  'y' => -80, 'w' => 80,  'color' => '#4a5a3a'],
            ['x' => 400,  'y' => -40, 'w' => 80,  'color' => '#4a5a3a'],
            ['x' => 520,  'y' => -100,'w' => 100, 'color' => '#4a5a3a'],

            // High platforms
            ['x' => 320,  'y' => -180,'w' => 70,  'color' => '#5a4a3a'],
            ['x' => 180,  'y' => -240,'w' => 90,  'color' => '#5a4a3a'],
            ['x' => 450,  'y' => -220,'w' => 80,  'color' => '#5a4a3a'],

            // Left side exploration
            ['x' => -380, 'y' => 60,  'w' => 120, 'color' => '#3a4a5a'],
            ['x' => -520, 'y' => -10, 'w' => 100, 'color' => '#3a4a5a'],
            ['x' => -420, 'y' => -100,'w' => 80,  'color' => '#3a4a5a'],
            ['x' => -560, 'y' => -160,'w' => 120, 'color' => '#3a4a5a'],
        ];

        foreach ($platforms as $i => $p) {
            $this->platform(
                $builder,
                "Platform_{$i}",
                (float) $p['x'],
                (float) $p['y'],
                (float) $p['w'],
                20.0,
                $p['color'],
            );
        }

        // Decorative elements — small colored squares scattered around
        $decorations = [
            ['x' => -180, 'y' => 90,  's' => 8, 'color' => '#ffcc44'],
            ['x' => -40,  'y' => 10,  's' => 8, 'color' => '#ffcc44'],
            ['x' => 130,  'y' => -50, 's' => 8, 'color' => '#ffcc44'],
            ['x' => 300,  'y' => -110,'s' => 10,'color' => '#ff6644'],
            ['x' => 520,  'y' => -130,'s' => 8, 'color' => '#ffcc44'],
            ['x' => 200,  'y' => -270,'s' => 12,'color' => '#ff6644'],
            ['x' => -500, 'y' => -40, 's' => 8, 'color' => '#ffcc44'],
            ['x' => -400, 'y' => -130,'s' => 10,'color' => '#ff6644'],
        ];

        foreach ($decorations as $i => $d) {
            $builder->entity("Coin_{$i}")
                ->with(new Transform2D(position: new Vec2((float) $d['x'], (float) $d['y'])))
                ->with(new ColorRect((float) $d['s'], (float) $d['s'], Color::hex($d['color']), cornerRadius: 2.0));
        }
    }

    private function platform(
        SceneBuilder $builder,
        string $name,
        float $x,
        float $y,
        float $width,
        float $height,
        string $color,
    ): void {
        $builder->entity($name)
            ->with(new Transform2D(position: new Vec2($x, $y)))
            ->with(new ColorRect($width, $height, Color::hex($color), cornerRadius: 3.0))
            ->with(new BoxCollider2D(size: new Vec2($width, $height)))
            ->with(new RigidBody2D(isKinematic: true))
            ->with(new Platform());
    }
}
