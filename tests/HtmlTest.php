<?php

declare(strict_types=1);

namespace yiiunit\extensions\bootstrap5;

use yii\base\DynamicModel;
use yii\bootstrap5\Html;

/**
 * @group bootstrap5
 */
class HtmlTest extends TestCase
{
    /**
     * @dataProvider \yiiunit\extensions\bootstrap5\providers\Data::staticControl
     *
     */
    public function testStaticControl(string $value, array $options, string $expectedHtml): void
    {
        $this->assertEquals($expectedHtml, Html::staticControl($value, $options));
    }

    public function testRadioList(): void
    {
        $this->assertEquals('<div></div>', Html::radioList('test'));

        $dataItems = [
            'value1' => 'text1',
            'value2' => 'text2',
        ];

        Html::$counter = 0;

        $expected = <<<HTML
        <div><div class="form-check"><input type="radio" id="i0" class="form-check-input" name="test" value="value1">
        <label class="form-check-label" for="i0">text1</label></div>
        <div class="form-check"><input type="radio" id="i1" class="form-check-input" name="test" value="value2" checked>
        <label class="form-check-label" for="i1">text2</label></div></div>
        HTML;
        $this->assertEqualsWithoutLE($expected, Html::radioList('test', ['value2'], $dataItems));

        Html::$counter = 0;
        $expected = <<<HTML
        <div>0<label>text1 <input type="radio" name="test" value="value1"></label>
        1<label>text2 <input type="radio" name="test" value="value2" checked></label></div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Html::radioList(
                'test',
                ['value2'],
                $dataItems,
                [
                    'item' => static fn ($index, $label, $name, $checked, $value) => $index .
                        Html::label($label . ' ' . Html::radio($name, $checked, ['value' => $value])),
                ],
            )
        );

        Html::$counter = 0;
        $expected = <<<HTML
        <div><div class="form-check"><input type="radio" id="i0" class="form-check-input" name="test" value="value">
        <label class="form-check-label" for="i0">label&amp;</label></div></div>
        HTML;
        $this->assertEqualsWithoutLE($expected, Html::radioList('test', [], ['value' => 'label&']));

        Html::$counter = 0;
        $expected = <<<HTML
        <div><div class="form-check"><input type="radio" id="i0" class="form-check-input" name="test" value="value">
        <label class="form-check-label" for="i0">label&</label></div></div>
        HTML;
        $this->assertEqualsWithoutLE($expected, Html::radioList('test', [], ['value' => 'label&'], ['encode' => false]));
    }

    public function testCheckboxList(): void
    {
        $this->assertEquals('<div></div>', Html::checkboxList('test'));

        $dataItems = [
            'value1' => 'text1',
            'value2' => 'text2',
        ];

        Html::$counter = 0;

        $expected = <<<HTML
        <div><div class="form-check"><input type="checkbox" id="i0" class="form-check-input" name="test[]" value="value1">
        <label class="form-check-label" for="i0">text1</label></div>
        <div class="form-check"><input type="checkbox" id="i1" class="form-check-input" name="test[]" value="value2" checked>
        <label class="form-check-label" for="i1">text2</label></div></div>
        HTML;
        $this->assertEqualsWithoutLE($expected, Html::checkboxList('test', ['value2'], $dataItems));


        Html::$counter = 0;
        $expected = <<<HTML
        <div>0<label>text1 <input type="checkbox" name="test[]" value="value1"></label>
        1<label>text2 <input type="checkbox" name="test[]" value="value2" checked></label></div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Html::checkboxList(
                'test',
                ['value2'],
                $dataItems,
                [
                    'item' => static fn ($index, $label, $name, $checked, $value) => $index .
                        Html::label($label . ' ' . Html::checkbox($name, $checked, ['value' => $value])),
                ],
            )
        );

        Html::$counter = 0;
        $expected = <<<HTML
        <div><div class="form-check"><input type="checkbox" id="i0" class="form-check-input" name="test[]" value="value" checked>
        <label class="form-check-label" for="i0">label&amp;</label></div></div>
        HTML;
        $this->assertEqualsWithoutLE($expected, Html::checkboxList('test', 'value', ['value' => 'label&']));

        Html::$counter = 0;
        $expected = <<<HTML
        <div><div class="form-check"><input type="checkbox" id="i0" class="form-check-input" name="test[]" value="value" checked>
        <label class="form-check-label" for="i0">label&</label></div></div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Html::checkboxList('test', 'value', ['value' => 'label&'], ['encode' => false]),
        );
    }

    public function testError(): void
    {
        $model = new DynamicModel();

        $model->addError('foo', 'Some error message.');
        $this->assertEquals('<div class="invalid-feedback">Some error message.</div>', Html::error($model, 'foo'));
        $this->assertEquals(
            '<div class="custom-class">Some error message.</div>',
            Html::error($model, 'foo', ['class' => 'custom-class']),
        );
        $this->assertEquals('<div>Some error message.</div>', Html::error($model, 'foo', ['class' => null]));
        $this->assertEquals(
            '<p class="invalid-feedback">Some error message.</p>',
            Html::error($model, 'foo', ['tag' => 'p']),
        );
    }
}
