<?php

declare(strict_types=1);

/**
 * Input mapping configuration.
 *
 * Axes return float values from -1.0 to 1.0.
 * Actions return boolean pressed/released states.
 */
return [
    'axes' => [
        'move_x' => [
            'positive' => [GLFW_KEY_D, GLFW_KEY_RIGHT],
            'negative' => [GLFW_KEY_A, GLFW_KEY_LEFT],
        ],
        'move_y' => [
            'positive' => [GLFW_KEY_S, GLFW_KEY_DOWN],
            'negative' => [GLFW_KEY_W, GLFW_KEY_UP],
        ],
    ],
    'actions' => [
        'confirm' => [GLFW_KEY_ENTER, GLFW_KEY_SPACE],
        'cancel'  => [GLFW_KEY_ESCAPE],
    ],
];
