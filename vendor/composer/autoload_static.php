<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit642612a7f6c7033dc615213a79b55309
{
    public static $prefixLengthsPsr4 = array (
        't' => 
        array (
            'think\\composer\\' => 15,
            'think\\' => 6,
        ),
        'a' => 
        array (
            'app\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'think\\composer\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-installer/src',
        ),
        'think\\' => 
        array (
            0 => __DIR__ . '/../..' . '/thinkphp/library/think',
        ),
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/application',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit642612a7f6c7033dc615213a79b55309::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit642612a7f6c7033dc615213a79b55309::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
