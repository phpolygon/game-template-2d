<?php

declare(strict_types=1);

namespace App;

use App\System\PlayerSystem;
use PHPolygon\Engine;
use PHPolygon\EngineConfig;
use PHPolygon\System\Camera2DSystem;
use PHPolygon\System\Physics2DSystem;

class Game
{
    public static function run(): void
    {
        $engine = new Engine(new EngineConfig(
            title: 'PHPolygon 2D — Top-Down Explorer',
            width: 1280,
            height: 720,
            targetTickRate: 60.0,
            assetsPath: __DIR__ . '/../assets',
        ));

        $engine->onInit(function (Engine $engine) {
            $engine->world->addSystem(new Camera2DSystem($engine->camera2D));
            $engine->world->addSystem(new Physics2DSystem(events: $engine->events));
            $engine->world->addSystem(new PlayerSystem($engine->input, $engine->renderer2D));

            $engine->scenes->register('menu', Scene\MenuScene::class);
            $engine->scenes->register('main', Scene\MainScene::class);

            $engine->scenes->loadScene('menu');
        });

        $engine->onRender(function (Engine $engine, float $interpolation) {
            $fps = $engine->gameLoop->getAverageFps();
            $engine->renderer2D->drawText(
                sprintf('FPS: %.0f', $fps),
                10, 10, 14,
                \PHPolygon\Rendering\Color::white(),
            );
        });

        $engine->run();
    }
}
