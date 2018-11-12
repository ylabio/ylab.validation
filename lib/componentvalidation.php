<?php

namespace YLab\Validation;

use Bitrix\Main\Application;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\HttpRequest;

/**
 * Class ComponentValidation
 * @package YLab\Validation
 */
abstract class ComponentValidation extends \CBitrixComponent
{
    /**
     * @var HttpApplication
     */
    protected $oApplication;
    /**
     * @var HttpRequest
     */
    protected $oRequest;
    /**
     * @var \Illuminate\Validation\Validator
     */
    protected $oValidator;

    /**
     * ComponentValidation constructor.
     * @param \CBitrixComponent|null $component
     * @param string $sFile
     * @throws \Bitrix\Main\IO\InvalidPathException
     * @throws \Bitrix\Main\SystemException
     * @throws \Exception
     */
    public function __construct(\CBitrixComponent $component = null, $sFile = __FILE__)
    {
        $this->oApplication = Application::getInstance();
        $this->oRequest = $this->oApplication->getContext()->getRequest();
        $this->oValidator = ValidatorHelper::makeCustomValidator([], $this->rules(), $sFile, LANGUAGE_ID);

        parent::__construct($component);
    }

    /**
     * Массив правил валидации
     * @return array
     */
    abstract protected function rules();
}
