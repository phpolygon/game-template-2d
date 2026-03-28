<?php

declare(strict_types=1);

namespace App;

use App\System\PlatformerRenderSystem;
use App\System\PlayerSystem;
use PHPolygon\Engine;
use PHPolygon\EngineConfig;
use PHPolygon\Math\Vec2;
use PHPolygon\Rendering\Color;
use PHPolygon\System\Camera2DSystem;
use PHPolygon\System\Physics2DSystem;

class Game
{
    public static function run(): void
    {
        $engine = new Engine(new EngineConfig(
            title: 'PHPolygon 2D — Platformer',
            width: 1280,
            height: 720,
            targetTickRate: 60.0,
        ));

        $engine->onInit(function (Engine $engine) {
            $engine->world->addSystem(new Camera2DSystem($engine->camera2D));
            $engine->world->addSystem(new Physics2DSystem(
                gravity: new Vec2(0.0, 980.0),
                events: $engine->events,
            ));
            $engine->world->addSystem(new PlayerSystem($engine->input));
            $engine->world->addSystem(new PlatformerRenderSystem(
                $engine->renderer2D,
                $engine->camera2D,
            ));

            $engine->scenes->register('main', Scene\MainScene::class);
            $engine->scenes->loadScene('main');
        });

        $engine->onRender(function (Engine $engine, float $interpolation) {
            $fps = $engine->gameLoop->getAverageFps();
            $engine->renderer2D->drawText(
                sprintf('FPS: %.0f', $fps),
                10.0, 10.0, 14,
                Color::white(),
            );
            $engine->renderer2D->drawText(
                'WASD / Arrows to move, SPACE to jump',
                10.0, 30.0, 12,
                Color::hex('#666677'),
            );
        });

        $engine->run();
    }
}
