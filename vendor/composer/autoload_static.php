<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita851ad921c7570be472641cce3f07cf3
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Fusedsoftware\\Bigcommerce\\' => 26,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Fusedsoftware\\Bigcommerce\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita851ad921c7570be472641cce3f07cf3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita851ad921c7570be472641cce3f07cf3::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
