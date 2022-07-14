# Anti-Spam Form for Yii2 Framework

**Anti-Spam Form** is a form replacement component for the [Yii2 Framework](https://www.yiiframework.com) for creating anti-spam forms that are invisible in HTML code to spam bots & harvesting tools.


## What's the idea behind anti-spam forms?

The concept is simple. Spam bots in most cases are simple tools. They will read your website, look for `<form>` HTML tags and artificially fill up the form fields and post it.

The usual spam bot is a web scraper written in Python retrieving raw HTML webpage content **without** evaluating JS code.

But one thing **missed** in anti-spam detection methods is that spam bots usually cannot process JavaScript code. For a good reason. It's time-consuming and requires JS interpreter to evaluate the JS code within the web page. And it's not that easy to incorporate JS evaluation without using complex & slower execution engines usually based on Chromium etc.



## How to break spam bots?

Simple - put the HTML forms wrapped within JS code. Not the entire form, but the starting `<form>` tag with all its properties.

Suddenly the spam bots are unable to find it. Less spam without too much work.

## How to install Anti-Spam Form component?

Install it first. Preferred way of WebApi interface installation is via [composer](https://getcomposer.org/).

Run:

```
php composer.phar require --prefer-dist pelock/yii2-anti-spam-form "*"
```

Or add this entry:

```
"pelock/yii2-anti-spam-form": "*"
```

directly to your `composer.json` in require section.

Installation package is available at https://packagist.org/packages/pelock/yii2-anti-spam-form


## How to use Anti-Spam Form?

Replace your regular Yii2 `ActiveForm` component:


```php
<div class="active-form">
<?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'email')->textInput(['type' => 'email']) ?>
    <?= $form->field($model, 'subject') ?>
    <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>

    <?php //echo Html::submitButton('Send', ['class' => 'btn btn-block btn-primary', 'name' => 'contact-button']) ?>

    <?php echo $form->field($model, 'verifyCode')->widget(Captcha::className(), [ 'template' => '<div class="row"><div class="col-xs-4 col-sm-3 col-md-3">{image}</div><div class="col-xs-2 col-sm-3 col-md-3">{input}</div><div class="col-xs-6 col-sm-6 col-md-6">'. Html::submitButton('Send', ['class' => 'btn btn-block btn-primary', 'name' => 'contact-button']) .'</div></div>', ]) ?>
<?php ActiveForm::end(); ?>
</div>

```

with `AntiSpamForm`:


```php
// include AntiSpamForm
use pelock\antispamform\AntiSpamForm;

...

<div class="active-form">
<!--replace here -->
<?php $form = AntiSpamForm::begin(['id' => 'contact-form']); ?>
    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'email')->textInput(['type' => 'email']) ?>
    <?= $form->field($model, 'subject') ?>
    <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>

    <?php //echo Html::submitButton('Send', ['class' => 'btn btn-block btn-primary', 'name' => 'contact-button']) ?>

    <?php echo $form->field($model, 'verifyCode')->widget(Captcha::className(), [ 'template' => '<div class="row"><div class="col-xs-4 col-sm-3 col-md-3">{image}</div><div class="col-xs-2 col-sm-3 col-md-3">{input}</div><div class="col-xs-6 col-sm-6 col-md-6">'. Html::submitButton('Send', ['class' => 'btn btn-block btn-primary', 'name' => 'contact-button']) .'</div></div>', ]) ?>
<!--and here -->    
<?php AntiSpamForm::end(); ?>
</div>

```

That's all! The forms are going to work exactly the same, the only difference is the output HTML code.

## Generated HTML code

What's the difference between the usual `ActiveForm` output HTML code and `AntiSpamForm` code?

Lets take a look. Before:

```html
<div class="active-form">
  <!--visible <form> tag -->  
  <form id="contact-form" action="/contact" method="post">
    <input type="hidden" name="_csrf" value="u18o4NxJC5lZEhHhjpMTd-c7p3ZzYzl0wvsXMiefJJ_0HWGpr1pm6x5Qa4vnpVw5o1yXNzEQ7USUlE9HVNlGzQ==">
    <div class="form-group field-contactform-name required">
      <label class="control-label" for="contactform-name">Name</label>
      <input type="text" id="contactform-name" class="form-control" name="ContactForm[name]" aria-required="true">
      <p class="help-block help-block-error"></p>
    </div>
    <div class="form-group field-contactform-email required">
      <label class="control-label" for="contactform-email">Email</label>
      <input type="email" id="contactform-email" class="form-control" name="ContactForm[email]" aria-required="true">
      <p class="help-block help-block-error"></p>
    </div>
    <div class="form-group field-contactform-subject required">
      <label class="control-label" for="contactform-subject">Subject</label>
      <input type="text" id="contactform-subject" class="form-control" name="ContactForm[subject]" aria-required="true">
      <p class="help-block help-block-error"></p>
    </div>
    <div class="form-group field-contactform-body required">
      <label class="control-label" for="contactform-body">Body</label>
      <textarea id="contactform-body" class="form-control" name="ContactForm[body]" rows="6" aria-required="true"></textarea>
      <p class="help-block help-block-error"></p>
    </div>
    <div class="form-group field-contactform-verifycode">
      <label class="control-label" for="contactform-verifycode">Verification Code</label>
      <div class="row">
        <div class="col-xs-4 col-sm-3 col-md-3">
          <img id="contactform-verifycode-image" src="/site/captcha?v=62cff29d6ebe55.89254929" alt="">
        </div>
        <div class="col-xs-2 col-sm-3 col-md-3">
          <input type="text" id="contactform-verifycode" class="form-control" name="ContactForm[verifyCode]">
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
          <button type="submit" class="btn btn-block btn-primary" name="contact-button">Send</button>
        </div>
      </div>
      <p class="help-block help-block-error"></p>
    </div>
  </form>
</div>
```

After:

```html
<div class="active-form">
  <!-- <form> tag dynamically generated with the JavaScript code -->    
  <script>
    document.write(atob("PGZvcm0gaWQ9ImNvbnRhY3QtZm9ybSIgYWN0aW9uPSIvY29udGFjdCIgbWV0aG9kPSJwb3N0Ij4KPGlucHV0IHR5cGU9ImhpZGRlbiIgbmFtZT0iX2NzcmYiIHZhbHVlPSJ1MFczN0JLaDJOQko2Q2lVeTFxc3R3OEp6aC1mcUxxOXRnOEpQem9CUjZfMEJfNmxYX0sxb2c2cVV2NmliT1A1UzI3LVh0M2F6bzNnWUZGS1NVY2xfUT09Ij4="));
  </script>
  <div class="form-group field-contactform-name required">
    <label class="control-label" for="contactform-name">Name</label>
    <input type="text" id="contactform-name" class="form-control" name="ContactForm[name]" aria-required="true">
    <p class="help-block help-block-error"></p>
  </div>
  <div class="form-group field-contactform-email required">
    <label class="control-label" for="contactform-email">Email</label>
    <input type="email" id="contactform-email" class="form-control" name="ContactForm[email]" aria-required="true">
    <p class="help-block help-block-error"></p>
  </div>
  <div class="form-group field-contactform-subject required">
    <label class="control-label" for="contactform-subject">Subject</label>
    <input type="text" id="contactform-subject" class="form-control" name="ContactForm[subject]" aria-required="true">
    <p class="help-block help-block-error"></p>
  </div>
  <div class="form-group field-contactform-body required">
    <label class="control-label" for="contactform-body">Body</label>
    <textarea id="contactform-body" class="form-control" name="ContactForm[body]" rows="6" aria-required="true"></textarea>
    <p class="help-block help-block-error"></p>
  </div>
  <div class="form-group field-contactform-verifycode">
    <label class="control-label" for="contactform-verifycode">Verification Code</label>
    <div class="row">
      <div class="col-xs-4 col-sm-3 col-md-3">
        <img id="contactform-verifycode-image" src="/site/captcha?v=62cff214a00af2.73036299" alt="">
      </div>
      <div class="col-xs-2 col-sm-3 col-md-3">
        <input type="text" id="contactform-verifycode" class="form-control" name="ContactForm[verifyCode]">
      </div>
      <div class="col-xs-6 col-sm-6 col-md-6">
        <button type="submit" class="btn btn-block btn-primary" name="contact-button">Send</button>
      </div>
    </div>
    <p class="help-block help-block-error"></p>
  </div>
  </form>
</div>
```

The JavaScript code generates output `<form>` element with all of its properties. Simple & effective solution against spam bots and web scrapers, harvesters etc.

## Should I remove CAPTCHA validations?

No. You should leave your `CAPTCHA` verifications in place, because some bots are able to run JS code, so the **Anti-Spam Form** will provide an additional layer of anti-spam protection.

## Bugs, questions, feature requests

Hope you like it. For questions, bug & feature requests visit my site:

Bartosz Wójcik | https://www.pelock.com