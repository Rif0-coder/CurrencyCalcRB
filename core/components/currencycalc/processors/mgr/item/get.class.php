<?php

class CurrencyCalcItemGetProcessor extends modObjectGetProcessor
{
    public $objectType = 'CurrencyCalcItem';
    public $classKey = 'CurrencyCalcItem';
    public $languageTopics = array('currencycalc:default');
    public $permission = 'view';

    /**
     * We doing special check of permission
     * because of our objects is not an instances of modAccessibleObject
     * @return mixed
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        return parent::process();
    }
}

return 'CurrencyCalcItemGetProcessor';