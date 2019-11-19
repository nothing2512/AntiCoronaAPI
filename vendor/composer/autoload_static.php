<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit226ec2eedeea845ea69c4c9842e01958
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Tour\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Tour\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit226ec2eedeea845ea69c4c9842e01958::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit226ec2eedeea845ea69c4c9842e01958::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}