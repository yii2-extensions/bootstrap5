<?php

declare(strict_types=1);

namespace yiiunit\extensions\bootstrap5;

use Yii;
use yii\bootstrap5\Toast;
use yii\web\View;

/**
 * @group bootstrap5
 */
class ToastTest extends TestCase
{
    public function testBodyOptions(): void
    {
        Toast::$counter = 0;
        $out = Toast::widget(
            [
                'bodyOptions' => [
                    'class' => 'toast-body test',
                    'style' => ['text-align' => 'center'],
                ],
            ],
        );

        $expected = <<<HTML
        <div id="w0" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
        <strong class="me-auto"></strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body test" style="text-align: center;">


        </div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE($expected, $out);
    }

    public function testContainerOptions(): void
    {
        Toast::$counter = 0;

        ob_start();
        Toast::begin(
            [
                'title' => 'Toast title',
                'dateTime' => time() - 60,
            ],
        );
        echo 'Woohoo, you\'re reading this text in a toast!';
        Toast::end();
        $out = ob_get_clean();

        $expected = <<<HTML
        <div id="w0" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
        <strong class="me-auto">Toast title</strong>
        <small class="text-muted">a minute ago</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
        Woohoo, you're reading this text in a toast!

        </div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE($expected, $out);
    }

    public function testDateTimeOptions(): void
    {
        Toast::$counter = 0;
        $out = Toast::widget(
            [
                'title' => 'Toast title',
                'dateTime' => time() - 60,
                'dateTimeOptions' => ['class' => ['toast-date-time'], 'style' => ['text-align' => 'right']],
            ],
        );

        $expected = <<<HTML
        <div id="w0" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
        <strong class="me-auto">Toast title</strong>
        <small class="toast-date-time text-muted" style="text-align: right;">a minute ago</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">


        </div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE($expected, $out);
    }

    public function testTitleOptions(): void
    {
        Toast::$counter = 0;
        $out = Toast::widget(
            [
                'title' => 'Toast title',
                'titleOptions' => ['tag' => 'h5', 'style' => ['text-align' => 'left']],
            ],
        );

        $expected = <<<HTML
        <div id="w0" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
        <h5 class="me-auto" style="text-align: left;">Toast title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">


        </div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE($expected, $out);
    }

    /**
     * @see https://github.com/yiisoft/yii2-bootstrap5/issues/5
     */
    public function testWidgetInitialization(): void
    {
        Toast::$counter = 0;
        ob_start();
        $toast = Toast::begin(
            [
                'title' => 'Toast title',
                'titleOptions' => ['tag' => 'h5', 'style' => ['text-align' => 'left']],
            ],
        );
        echo 'test';
        Toast::end();
        $out = ob_get_clean();

        $this->assertIsArray($toast->clientOptions);
        $this->assertCount(0, $toast->clientOptions);

        $js = Yii::$app->view->js[View::POS_READY];

        $this->assertIsArray($js);
        $options = array_shift($js);

        $this->assertContainsWithoutLE("(new bootstrap.Toast('#w0', {}));", $options);
    }

    /**
     * @see https://github.com/yiisoft/yii2-bootstrap5/issues/36
     */
    public function testWidgetNoInitialization(): void
    {
        Toast::$counter = 0;
        ob_start();
        $toast = Toast::begin(
            [
                'title' => 'Toast title',
                'clientOptions' => false,
                'titleOptions' => ['tag' => 'h5', 'style' => ['text-align' => 'left']],
            ],
        );
        echo 'test';
        Toast::end();
        $out = ob_get_clean();

        $this->assertFalse($toast->clientOptions);
        $this->assertArrayHasKey(View::POS_READY, Yii::$app->view->js);
    }

    public function testWidgetInitializationWithClientOptions(): void
    {
        Toast::$counter = 0;
        ob_start();
        $toast = Toast::begin(
            [
                'title' => 'Toast title',
                'clientOptions' => ['delay' => 1000],
                'titleOptions' => ['tag' => 'h5', 'style' => ['text-align' => 'left']],
            ],
        );
        echo 'test';
        Toast::end();
        $out = ob_get_clean();

        $this->assertArrayHasKey('delay', $toast->clientOptions);
        $this->assertArrayHasKey(View::POS_READY, Yii::$app->view->js);
        $js = Yii::$app->view->js[View::POS_READY];

        $this->assertIsArray($js);
        $options = array_shift($js);

        $this->assertContainsWithoutLE("(new bootstrap.Toast('#w0', {\"delay\":1000}));", $options);
    }
}
