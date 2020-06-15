<?php
namespace Stephane888\Debug;

require_once __DIR__ . '/../vendor/autoload.php';
use Kint\kint;
use Kint\Renderer\RichRenderer;

// use Kint\Renderer\Renderer;
// use kint\Utils;
// use Kint\Renderer\RichRenderer;
/*
 * require_once __DIR__ . '/../vendor/kint-php/kint/src/Kint.php';
 * require_once __DIR__ . '/../vendor/kint-php/kint/src/Utils.php';
 * require_once __DIR__ . '/../vendor/kint-php/kint/src/CallFinder.php';
 * require_once __DIR__ . '/../vendor/kint-php/kint/src/Renderer/RichRenderer.php';
 * require_once __DIR__ . '/../vendor/kint-php/kint/src/Renderer/Renderer.php';
 */
class DebugWbu {

  public static function kint_bug($logs = '', $max_depth = 3)
  {
    RichRenderer::$theme = __DIR__ . '/../assets/aante-light-custom.css';
    kint::$max_depth = $max_depth;
    kint::dump($logs);
  }
}