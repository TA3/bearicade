<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit60b8ac6251c3ae852cf76e87d81fe62e
{
    public static $files = array (
        'decc78cc4436b1292c6c0d151b19445c' => __DIR__ . '/..' . '/phpseclib/phpseclib/phpseclib/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'p' => 
        array (
            'phpseclib\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'phpseclib\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpseclib/phpseclib/phpseclib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit60b8ac6251c3ae852cf76e87d81fe62e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit60b8ac6251c3ae852cf76e87d81fe62e::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
