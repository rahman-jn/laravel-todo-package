<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3017d20aeec84777c16df4f342ac8432
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'Rahman\\Todos\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Rahman\\Todos\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3017d20aeec84777c16df4f342ac8432::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3017d20aeec84777c16df4f342ac8432::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3017d20aeec84777c16df4f342ac8432::$classMap;

        }, null, ClassLoader::class);
    }
}