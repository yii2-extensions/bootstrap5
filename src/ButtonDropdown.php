<?php
/**
 * @link https://www.yiiframework.com/
 *
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

declare(strict_types=1);

namespace yii\bootstrap5;

use Throwable;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * ButtonDropdown renders a group or split button dropdown bootstrap component.
 *
 * For example,
 *
 * ```php
 * // a button group using Dropdown widget
 * echo ButtonDropdown::widget([
 *     'label' => 'Action',
 *     'dropdown' => [
 *         'items' => [
 *             ['label' => 'DropdownA', 'url' => '/'],
 *             ['label' => 'DropdownB', 'url' => '#'],
 *         ],
 *     ],
 * ]);
 * ```
 *
 * @see https://getbootstrap.com/docs/5.1/components/buttons/
 * @see https://getbootstrap.com/docs/5.1/components/dropdowns/
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 */
class ButtonDropdown extends Widget
{
    /**
     * The css class part of the dropdown
     */
    public const DIRECTION_DOWN = 'down';
    /**
     * The css class part of dropleft
     */
    public const DIRECTION_LEFT = 'left';
    /**
     * The css class part of dropright
     */
    public const DIRECTION_RIGHT = 'right';
    /**
     * The css class part of dropup
     */
    public const DIRECTION_UP = 'up';

    /**
     * @var string|null the button label
     */
    public string|null $label = null;
    /**
     * @var array the HTML attributes of the button.
     *
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public array $buttonOptions = [];
    /**
     * @var array the configuration array for [[Dropdown]].
     */
    public array $dropdown = [];
    /**
     * @var string the drop-direction of the widget
     *
     * Possible values are 'left,' 'right,' 'up', or 'down' (default)
     */
    public string $direction = self::DIRECTION_DOWN;
    /**
     * @var bool whether to display a group of split-styled button groups.
     */
    public bool $split = false;
    /**
     * @var string the tag to use to render the button
     */
    public string $tagName = 'button';
    /**
     * @var bool whether the label should be HTML-encoded.
     */
    public bool $encodeLabel = true;
    /**
     * @var string name of a class to use for rendering dropdowns withing this widget. Defaults to [[Dropdown]].
     */
    public string $dropdownClass = Dropdown::class;
    /**
     * @var bool whether to render the container using the [[options]] as HTML attributes. If set to `false`,
     * the container element enclosing the button and dropdown will NOT be rendered.
     */
    public bool $renderContainer = true;

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        if (!isset($this->buttonOptions['id'])) {
            $this->buttonOptions['id'] = $this->options['id'] . '-button';
        }
        if ($this->label === null) {
            $this->label = Yii::t('yii/bootstrap5', 'Button');
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws Throwable
     *
     * @return string
     */
    public function run(): string
    {
        $html = $this->renderButton() . "\n" . $this->renderDropdown();

        if ($this->renderContainer) {
            Html::addCssClass($this->options, ['widget' => 'drop' . $this->direction, 'btn-group']);
            $options = $this->options;
            $tag = ArrayHelper::remove($options, 'tag', 'div');
            $html = Html::tag($tag, $html, $options);
        }

        // Set options id to button options id to ensure the correct css selector in plugin initialisation
        $this->options['id'] = $this->buttonOptions['id'];

        $this->registerPlugin('dropdown');

        return $html;
    }

    /**
     * Generates the button dropdown.
     *
     * @throws Throwable
     *
     * @return string the rendering result.
     */
    protected function renderButton(): string
    {
        Html::addCssClass($this->buttonOptions, ['widget' => 'btn']);
        $label = $this->label;
        if ($this->encodeLabel) {
            $label = Html::encode($label);
        }

        $buttonOptions = $this->buttonOptions;

        if ($this->split) {
            $this->buttonOptions['data'] = ['bs-toggle' => 'dropdown'];
            $this->buttonOptions['aria'] = ['expanded' => 'false'];
            Html::addCssClass($this->buttonOptions, ['toggle' => 'dropdown-toggle dropdown-toggle-split']);
            unset($buttonOptions['id']);
            $splitButton = Button::widget([
                'label' => '<span class="visually-hidden">' . Yii::t('yii/bootstrap5', 'Toggle Dropdown') . '</span>',
                'encodeLabel' => false,
                'options' => $this->buttonOptions,
                'view' => $this->getView(),
            ]);
        } else {
            Html::addCssClass($buttonOptions, ['toggle' => 'dropdown-toggle']);
            $buttonOptions['data'] = ['bs-toggle' => 'dropdown'];
            $buttonOptions['aria'] = ['expanded' => 'false'];
            $splitButton = '';
        }

        if (isset($buttonOptions['href'])) {
            if (is_array($buttonOptions['href'])) {
                $buttonOptions['href'] = Url::to($buttonOptions['href']);
            }
        } elseif ($this->tagName === 'a') {
            $buttonOptions['href'] = '#';
            $buttonOptions['role'] = 'button';
        }

        return Button::widget([
            'tagName' => $this->tagName,
            'label' => $label,
            'options' => $buttonOptions,
            'encodeLabel' => false,
            'view' => $this->getView(),
        ]) . "\n" . $splitButton;
    }

    /**
     * Generates the dropdown menu.
     *
     * @throws Throwable
     *
     * @return string the rendering result.
     */
    protected function renderDropdown(): string
    {
        $config = $this->dropdown;
        $config['clientOptions'] = [];
        $config['view'] = $this->getView();
        /** @var Widget $dropdownClass */
        $dropdownClass = $this->dropdownClass;

        return $dropdownClass::widget($config);
    }
}
