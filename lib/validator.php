<?php

namespace YLab\Validation;

use Bitrix\Main\Localization\Loc;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;

/**
 * Class Validator
 * @package YLab\Validation
 */
class Validator
{
    /**
     * @param array $arData
     * @param array $arRules
     * @param string $sLang
     * @param array $arMessages Сообщения об ошибках
     * @param array $arCustoms Сообщения кастомных валидаторов, именование полей
     * @return \Illuminate\Validation\Validator
     * @throws \Exception
     *
     * @link http://laravel.su/docs/5.4/validation
     * @link https://packagist.org/packages/illuminate/validation#v5.5.0
     * @link https://github.com/illuminate/validation
     */
    public static function make(array $arData, array $arRules, $sLang = LANGUAGE_ID, array $arMessages = [], array $arCustoms = [])
    {
        if (!class_exists('\Illuminate\Validation\Validator')) {
            throw new \Exception(Loc::getMessage('YLAB_VALIDATION_LOAD_ERROR'));
        }

        $oTranslator = new Translator(new ArrayLoader(), $sLang);
        $oTranslator->addLines($arMessages, $sLang);

        return new \Illuminate\Validation\Validator(
            $oTranslator,
            $arData,
            $arRules,
            $arMessages,
            $arCustoms
        );
    }
}