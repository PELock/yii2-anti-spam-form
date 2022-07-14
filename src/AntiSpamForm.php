<?php
/**
 * Anti-Spam Form is a form replacement component for the Yii2 Framework for creating
 * anti-spam forms that are invisible in HTML code to spam bots & harvesting tools.
 *
 * Default Bootstrap 3 compatible form.
 *
 * @link https://www.pelock.com/
 * @copyright Copyright (c) 2022 PELock LLC
 * @license Apache-2.0
 */
namespace pelock\antispamform;

use Yii;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\helpers\Html;

class AntiSpamForm extends \yii\widgets\ActiveForm
{
	/**
	 * @var string the default field class name when calling [[field()]] to create a new field.
	 * @see fieldConfig
	 */
	public $fieldClass = 'yii\bootstrap\ActiveField';
	/**
	 * @var array HTML attributes for the form tag. Default is `[]`.
	 */
	public $options = [];
	/**
	 * @var string the form layout. Either 'default', 'horizontal' or 'inline'.
	 * By choosing a layout, an appropriate default field configuration will be applied.
	 * This will render the form fields with slightly different markup for each layout.
	 * You can override these defaults through [[fieldConfig]].
	 * @see \yii\bootstrap\ActiveField for details on Bootstrap 3 field configuration
	 */
	public $layout = 'default';


	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		if (!in_array($this->layout, ['default', 'horizontal', 'inline'])) {
			throw new InvalidConfigException('Invalid layout type: ' . $this->layout);
		}

		if ($this->layout !== 'default') {
			Html::addCssClass($this->options, 'form-' . $this->layout);
		}
		parent::init();
	}

	/**
	 * {@inheritdoc}
	 * @return ActiveField the created ActiveField object
	 */
	public function field($model, $attribute, $options = [])
	{
		return parent::field($model, $attribute, $options);
	}

	/**
	 * Runs the widget.
	 * This registers the necessary JavaScript code and renders the form open and close tags.
	 * @throws InvalidCallException if `beginField()` and `endField()` calls are not matching.
	 */
	public function run()
	{
		if (!empty($this->_fields)) {
			throw new InvalidCallException('Each beginField() should have a matching endField() call.');
		}

		$content = ob_get_clean();
		$html = Html::beginForm($this->action, $this->method, $this->options);

		$html = Html::script("document.write(atob(\"" . base64_encode($html) . "\"));");

		$html .= $content;

		if ($this->enableClientScript) {
			$this->registerClientScript();
		}

		$html .= Html::endForm();

		return $html;
	}
}
