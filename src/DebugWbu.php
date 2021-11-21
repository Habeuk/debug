<?php
namespace Stephane888\Debug;

use Kint\kint;
use Kint\Renderer\RichRenderer;
//
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\VarDumper;

// use Kint\Renderer\Renderer;
// use kint\Utils;
// use Kint\Renderer\RichRenderer;

/*
 * require_once __DIR__ . '/../vendor/kint-php/kint/src/Kint.php';
 * require_once __DIR__ . '/../vendor/kint-php/kint/src/Utils.php';
 * require_once __DIR__ . '/../vendor/kint-php/kint/src/CallFinder.php';
 * require_once __DIR__ . '/../vendor/kint-php/kint/src/Renderer/RichRenderer.php';
 * require_once __DIR__ . '/../vendor/kint-php/kint/src/Renderer/Renderer.php';
 *
 */

/**
 *
 * @author stephane
 *
 */
class DebugWbu
{

    public static function kint_bug($logs = '', $max_depth = 3)
    {
        RichRenderer::$theme = __DIR__ . '/../assets/aante-light-custom.css';
        RichRenderer::$always_pre_render = true;
        RichRenderer::$needs_pre_render = true;
        kint::$max_depth = $max_depth;
        kint::dump($logs);
        // $statics = kint::getStatics();
        // kint::createFromStatics($statics);
    }

    public static function VarDumperBug($var = '')
    {
        VarDumper::dump($var);
    }

    /**
     *
     * @see https://symfony.com/doc/current/components/var_dumper/advanced.html
     * @param mixed $var
     */
    public static function CustomVarDumper($var)
    {
        VarDumper::setHandler(function ($var) {
            $cloner = new VarCloner();
            $dumper = 'cli' === PHP_SAPI ? new CliDumper() : new HtmlDumper();
            return $dumper->dump($cloner->cloneVar($var));
        });
    }
}
