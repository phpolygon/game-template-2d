<?php

declare(strict_types=1);

namespace App\Scene;

use PHPolygon\Component\Camera2DComponent;
use PHPolygon\Component\Transform2D;
use PHPolygon\Engine;
use PHPolygon\Math\Vec2;
use PHPolygon\Rendering\Color;
use PHPolygon\Scene\Scene;
use PHPolygon\Scene\SceneBuilder;
use PHPolygon\Scene\SceneConfig;

class MenuScene extends Scene
{
    public function getName(): string
    {
        return 'menu';
    }

    public function getConfig(): SceneConfig
    {
        $config = new SceneConfig();
        $config->clearColor = Color::hex('#1a1a2e');
        return $config;
    }

    public function build(SceneBuilder $builder): void
    {
        $builder->entity('Camera')
            ->with(new Transform2D(position: Vec2::zero()))
            ->with(new Camera2DComponent(zoom: 1.0));
    }

    public function onActivate(Engine $engine): void
    {
        $engine->onRender(function (Engine $engine, float $interpolation) {
            $w = $engine->getConfig()->width;
            $h = $engine->getConfig()->height;

            $engine->renderer2D->drawText(
                'PHPolygon 2D Template',
                (float) ($w / 2 - 120), (float) ($h / 2 - 40), 28,
                Color::hex('#e0c97f'),
            );

            $engine->renderer2D->drawText(
                'Press ENTER to start',
                (float) ($w / 2 - 90), (float) ($h / 2 + 20), 18,
                Color::hex('#888899'),
            );

            if ($engine->input->isKeyPressed(GLFW_KEY_ENTER)) {
                $engine->scenes->loadScene('main');
            }
        });
    }
}
