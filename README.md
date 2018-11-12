# Модуль YLab Validation

[![Latest Stable Version](https://poser.pugx.org/ylab/validation/v/stable?format=flat-square)](https://packagist.org/packages/ylab/validation)
[![Total Downloads](https://poser.pugx.org/ylab/validation/downloads?format=flat-square)](https://packagist.org/packages/ylab/validation)
[![Latest Unstable Version](https://poser.pugx.org/ylab/validation/v/unstable?format=flat-square)](https://packagist.org/packages/ylab/validation)
[![License](https://poser.pugx.org/ylab/validation/license?format=flat-square)](https://packagist.org/packages/ylab/validation)

Модуль является оберткой над библиотекой валидации Laravel.
 * php: >=5.6.4
 * Bitrix: >= 17.0.0
 * illuminate/validation: >=5.4

## Установка с github

* Необходимо установить `illuminate/validation`, через composer:

    `php composer.phar install illuminate/validation 5.5`

* В файле `local/php_interface/init.php` подключить composer автозагрузчик:

    `require_once(dirname(__FILE__) . '/../vendor/autoload.php');`

* Копируем репозиторий:
    ```
    cd local/modules
    git clone git@github.com:ylabio/ylab.validation.git
    cd ylab.validation
    ```

* В папку `local/modules` будет склонирован репозиторий модуля, после этого, необходимо в панели администратора 
установить модуль: `Рабочий стол -> Marketplace -> Установленные решения`.

## Установка через composer

`composer require ylab/validation:dev-master`

Для установки в `local/modules`, необходимо в composer.json добавить:
```json
"extra": {
  //путь до папки local, например ../local, если composer.json лежит в папке local
  "bitrix-dir": ""
}
```

## Использование

Для реализации компонента с валидацией, можно использовать пример кода: 
`local/modules/ylab.validation/install/components/ylab/validation.test/class.php`.

В данном примере класс компонента наследуется от абстрактного класса `YLab\Validation\ComponentValidation`, который на 
себя берет функцию инициализации объекта валидации и обязывает реализовать метод `rules()`.

В конструкторе абстрактного класса объект валидатора инициализируется следующим образом:
```php
$this->oValidator = ValidatorHelper::makeCustomValidator([], $this->rules(), $sFile, LANGUAGE_ID);
```
В методе `ValidatorHelper::makeCustomValidator()` подключаются языковые файлы компонента и модуля. Языковые файлы модуля 
содержат описание стандартных ошибок, наследуемый компонент может заменять предустановленные фразы ошибок на собственные.

### Пример компонента
https://github.com/ylabio/ylab.validation/blob/master/install/components/ylab/validation.test/class.php

### Собственные правила валидации

Создать собственные правила валидации можно в компоненте в отдельном методе или методе `executeComponent()`:
```php
/**
 * При необходимости в компоненте можно реализовать дополнительные правила валидации, например, данный валидатор 
 * проверяет наличие пользователя в базе данных по ID.
 */
$this->oValidator->addExtension('user_exists', function($attribute, $value, $parameters, $validator) {
    $arValidate = UserTable::getList([
        'select' => ['ID'],
        'filter' => ['=ID' => $value],
        'limit' => 1
    ])->fetch();

    return $arValidate['ID'] ? true : false;
});
```