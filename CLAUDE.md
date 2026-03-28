# CLAUDE.md — PHPolygon 2D Game Project

This is a **PHPolygon 2D game project**. Claude Code is the primary authoring tool.

---

## Engine

- **PHPolygon** is a PHP-native game engine. Require via `phpolygon/phpolygon`.
- 2D rendering via OpenGL 4.1 / NanoVG (php-glfw extension)
- ECS architecture: Entities have Components, Systems process them
- Scenes are PHP classes extending `Scene` with a `build(SceneBuilder)` method
- Fixed timestep game loop at 60Hz

## Project Structure

```
game.php            Entry point — bootstraps Engine, calls App\Game::run()
src/
  Game.php          Main class — EngineConfig, scene registration, system setup
  Scene/            Scene classes (extend PHPolygon\Scene\Scene)
  Component/        Game-specific components (extend AbstractComponent)
  System/           Game-specific systems (implement SystemInterface)
  Prefab/           Reusable entity templates (implement PrefabInterface)
assets/             Textures, audio, fonts (loaded at runtime)
config/             Input mappings, game configuration
tests/              PHPUnit tests (run headless without GPU)
build.json          Build configuration for standalone executables
```

## Conventions

- **Namespace:** `App\` maps to `src/` via PSR-4
- **Components:** Data holders with `#[Serializable]` and `#[Property]` attributes. No cross-entity logic.
- **Systems:** Cross-entity logic. Query entities via `$world->query(ComponentA::class, ComponentB::class)`.
- **Scenes:** Define entities in `build()` via `SceneBuilder` fluent API. Register in `Game.php`.
- **Prefabs:** Implement `PrefabInterface`. Used via `$builder->prefab(MyPrefab::class, 'name')`.
- **Input:** Use `$engine->input->isKeyDown(GLFW_KEY_W)` or InputMap for action/axis abstraction.

## Key Patterns

### Creating a new Entity in a Scene
```php
$builder->entity('Enemy')
    ->with(new Transform2D(position: new Vec2(100, 200)))
    ->with(new SpriteRenderer(width: 24, height: 24, color: Color::red()))
    ->with(new BoxCollider2D(size: new Vec2(24, 24)))
    ->with(new RigidBody2D());
```

### Creating a new Component
```php
#[Serializable]
class Health extends AbstractComponent {
    #[Property]
    public float $current = 100.0;
    #[Property]
    public float $max = 100.0;
}
```

### Creating a new System
```php
class HealthSystem extends AbstractSystem {
    public function update(World $world, float $dt): void {
        foreach ($world->query(Health::class) as $entity) {
            $health = $world->getComponent($entity->id, Health::class);
            if ($health->current <= 0) { $world->destroyEntity($entity->id); }
        }
    }
}
```

## Anti-Patterns
- Do NOT put cross-entity logic in Components
- Do NOT call GPU APIs from Systems — only the Renderer touches GPU
- Do NOT store runtime state in PHP files — use JSON via SaveManager
- Do NOT implement `toJson()`/`fromJson()` manually — use `#[Serializable]` attributes

## Running

```bash
composer install
php game.php                                          # Run the game
vendor/bin/phpunit                                    # Run tests (headless)
php -d phar.readonly=0 vendor/bin/phpolygon build     # Build standalone executable
```
