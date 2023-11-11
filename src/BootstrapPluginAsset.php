<?php

declare(strict_types=1);

/**
 * @link https://www.yiiframework.com/
 *
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace yii\bootstrap5;

use yii\web\AssetBundle;
use yii\bootstrap5\BootstrapAsset;

/**
 * Twitter Bootstrap 5 JavaScript bundle.
 */
class BootstrapPluginAsset extends AssetBundle
{
    /**
     * @inheritDoc
     */
    public $sourcePath = '@npm/bootstrap';

    /**
     * @inheritDoc
     */
    public $js = [
        'dist/js/bootstrap.bundle.js',
    ];

    /**
     * @inheritDoc
     */
    public $depends = [
        BootstrapAsset::class,
    ];
}
