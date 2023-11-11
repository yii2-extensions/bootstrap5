<?php
/**
 * @link https://www.yiiframework.com/
 *
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

declare(strict_types=1);

namespace yii\bootstrap5;

use Exception;
use yii\base\InvalidConfigException;

/**
 * ToggleButtonGroup allows rendering form inputs Checkbox/Radio toggle button groups.
 *
 * You can use this widget in an [[yii\bootstrap5\ActiveForm|ActiveForm]] using the [[yii\widgets\ActiveField::widget()|widget()]]
 * method, for example like this:
 *
 * ```php
 * <?= $form->field($model, 'item_id')->widget(\yii\bootstrap5\ToggleButtonGroup::class, [
 *     'type' => \yii\bootstrap5\ToggleButtonGroup::TYPE_CHECKBOX
 *     'items' => [
 *         'fooValue' => 'BarLabel',
 *         'barValue' => 'BazLabel'
 *     ]
 * ]) ?>
 * ```
 *
 * @see https://getbootstrap.com/docs/5.1/components/buttons/#checkbox-and-radio-buttons
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @author Simon Karlen <simi.albi@outlook.com>
 */
class ToggleButtonGroup extends InputWidget
{
    /**
     * Checkbox type
     */
    public const TYPE_CHECKBOX = 'checkbox';
    /**
     * Radio type
     */
    public const TYPE_RADIO = 'radio';

    /**
     * @var string input type, can be [[TYPE_CHECKBOX]] or [[TYPE_RADIO]]
     */
    public string $type;
    /**
     * @var array the data item used to generate the checkboxes.
     * The array values are the labels, while the array keys are the corresponding checkbox or radio values.
     */
    public array $items = [];
    /**
     * @var array the HTML attributes for the label (button) tag.
     *
     * @see Html::checkbox()
     * @see Html::radio()
     */
    public array $labelOptions = [
        'class' => ['btn', 'btn-outline-secondary'],
    ];
    /**
     * @var bool whether the item labels should be HTML-encoded.
     */
    public bool $encodeLabels = true;

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();
        $this->registerPlugin('button');
        Html::addCssClass($this->options, ['widget' => 'btn-group']);
        $this->options['role'] = 'group';
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     * @throws InvalidConfigException
     *
     * @return string
     */
    public function run(): string
    {
        if (!isset($this->options['item'])) {
            $this->options['item'] = [$this, 'renderItem'];
        }
        switch ($this->type) {
            case 'checkbox':
                if ($this->hasModel()) {
                    return Html::activeCheckboxList($this->model, $this->attribute, $this->items, $this->options);
                }
                return Html::checkboxList($this->name, $this->value, $this->items, $this->options);

            case 'radio':
                if ($this->hasModel()) {
                    return Html::activeRadioList($this->model, $this->attribute, $this->items, $this->options);
                }
                return Html::radioList($this->name, $this->value, $this->items, $this->options);

            default:
                throw new InvalidConfigException("Unsupported type '$this->type'");
        }
    }

    /**
     * Default callback for checkbox/radio list item rendering.
     *
     * @param int $index item index.
     * @param string $label item label.
     * @param string $name input name.
     * @param bool $checked whether the value is checked or not.
     * @param string $value input value.
     *
     * @return string generated HTML.
     *
     * @see Html::checkbox()
     * @see Html::radio()
     */
    public function renderItem(int $index, string $label, string $name, bool $checked, string $value): string
    {
        unset($index);
        $labelOptions = $this->labelOptions;
        $labelOptions['wrapInput'] = false;
        Html::addCssClass($labelOptions, ['widget' => 'btn']);
        $type = $this->type;
        if ($this->encodeLabels) {
            $label = Html::encode($label);
        }
        $options = [
            'label' => $label,
            'labelOptions' => $labelOptions,
            'autocomplete' => 'off',
            'value' => $value,
        ];
        Html::addCssClass($options, ['widget' => 'btn-check']);

        return Html::$type($name, $checked, $options);
    }
}
