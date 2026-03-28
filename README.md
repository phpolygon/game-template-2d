# PHPolygon 2D Game Template

A top-down explorer starter project built with [PHPolygon](https://github.com/hmennen90/phpolygon).

## Getting Started

```bash
composer create-project phpolygon/game-template-2d my-game
cd my-game
php game.php
```

## Controls

| Key | Action |
|-----|--------|
| WASD / Arrow Keys | Move player |
| Enter | Start game (from menu) |
| Escape | Quit |

## Project Structure

| Directory | Purpose |
|-----------|---------|
| `src/Scene/` | Game scenes (menu, gameplay) |
| `src/Component/` | Custom ECS components |
| `src/System/` | Game logic systems |
| `src/Prefab/` | Reusable entity templates |
| `assets/` | Textures, audio, fonts |
| `config/` | Input mappings |
| `tests/` | PHPUnit tests (headless) |

## Building

Build a standalone executable:

```bash
php -d phar.readonly=0 vendor/bin/phpolygon build
```

## Testing

```bash
vendor/bin/phpunit
```

Tests run in headless mode — no GPU required.

## AI Authoring

This project is designed for AI-first authoring with Claude Code. See `CLAUDE.md` for conventions and patterns.
