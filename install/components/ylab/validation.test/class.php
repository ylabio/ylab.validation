<?php
namespace YLab\Validation\Components;

use Bitrix\Main\UserTable;
use YLab\Validation\ComponentValidation;
use YLab\Validation\ValidatorHelper;

/**
 * Class ValidationTestComponent
 * Компонент пример использования модуля ylab.validation в разработке
 *
 * @package YLab\Validation\Components
 */
class ValidationTestComponent extends ComponentValidation
{
    /**
     * ValidationTestComponent constructor.
     * @param \CBitrixComponent|null $component
     * @param string $sFile
     * @throws \Bitrix\Main\IO\InvalidPathException
     * @throws \Bitrix\Main\SystemException
     * @throws \Exception
     */
    public function __construct(\CBitrixComponent $component = null, $sFile = __FILE__)
    {
        parent::__construct($component, $sFile);
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function executeComponent()
    {
        /**
         * При необходимости в компоненте можно реализовать дополнительные правила валидации, например:
         */
        $this->oValidator->addExtension('user_exists', function($attribute, $value, $parameters, $validator) {
            $arValidate = UserTable::getList([
                'select' => ['ID'],
                'filter' => ['=ID' => $value],
                'limit' => 1
            ])->fetch();

            return $arValidate['ID'] ? true : false;
        });

        /**
         * Непосредственно валидация и действия при успехе и фейле
         */
        if ($this->oRequest->isPost() && check_bitrix_sessid()) {
            $this->oValidator->setData($this->oRequest->toArray());

            if ($this->oValidator->passes()) {
                $this->arResult['SUCCESS'] = true;
            } else {
                $this->arResult['ERRORS'] = ValidatorHelper::errorsToArray($this->oValidator);
            }
        }

        $this->includeComponentTemplate();
    }

    /**
     * @return array
     */
    protected function rules()
    {
        /**
         * Перед формированием массива правил валидации мы можем вытащить все необходимые данные из различных источников
         */
        return [
            'user' => 'required|numeric|user_exists',
            'date' => 'required|date_format:d.m.Y',
            'rating' => 'required|min:1|max:5|numeric'
        ];
    }
}