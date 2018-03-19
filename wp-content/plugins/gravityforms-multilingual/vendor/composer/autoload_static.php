<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit24c605cc5acf41fc8c9329dc6832b8e4
{
    public static $prefixesPsr0 = array (
        'x' => 
        array (
            'xrstf\\Composer52' => 
            array (
                0 => __DIR__ . '/..' . '/xrstf/composer-php52/lib',
            ),
        ),
    );

    public static $classMap = array (
        'GFML_Migration' => __DIR__ . '/../..' . '/inc/gfml-migration.class.php',
        'GFML_String_Name_Helper' => __DIR__ . '/../..' . '/inc/gfml-string-name-helper.class.php',
        'GFML_TM_API' => __DIR__ . '/../..' . '/inc/gfml-tm-api.class.php',
        'Gravity_Forms_Multilingual' => __DIR__ . '/../..' . '/inc/gravity-forms-multilingual.class.php',
        'WPML_Cache_Directory' => __DIR__ . '/..' . '/wpml-shared/wpml-lib-cache/src/cache/class-wpml-cache-directory.php',
        'WPML_Dependencies' => __DIR__ . '/..' . '/wpml-shared/wpml-lib-dependencies/src/dependencies/class-wpml-dependencies.php',
        'WPML_GFML_Filter_Country_Field' => __DIR__ . '/../..' . '/classes/wpml-gfml-filter-country-field.php',
        'WPML_GFML_Filter_Field_Meta' => __DIR__ . '/../..' . '/classes/class-wpml-gfml-filter-field-meta.php',
        'WPML_GFML_Plugin_Activation' => __DIR__ . '/../..' . '/classes/class-wpml-gfml-plugin-activation.php',
        'WPML_GFML_Requirements' => __DIR__ . '/../..' . '/classes/class-wpml-gfml-requirements.php',
        'WPML_GF_Quiz' => __DIR__ . '/../..' . '/classes/compatibility/quiz/class-wpml-gf-quiz.php',
        'WPML_GF_Survey' => __DIR__ . '/../..' . '/classes/compatibility/survey/wpml-gf-survey.php',
        'xrstf\\Composer52\\AutoloadGenerator' => __DIR__ . '/..' . '/xrstf/composer-php52/lib/xrstf/Composer52/AutoloadGenerator.php',
        'xrstf\\Composer52\\Generator' => __DIR__ . '/..' . '/xrstf/composer-php52/lib/xrstf/Composer52/Generator.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit24c605cc5acf41fc8c9329dc6832b8e4::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit24c605cc5acf41fc8c9329dc6832b8e4::$classMap;

        }, null, ClassLoader::class);
    }
}