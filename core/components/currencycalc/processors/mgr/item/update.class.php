<?php

class CurrencyCalcItemUpdateProcessor extends modObjectUpdateProcessor
{
    /** @var CurrencyCalcItem $object */
    public $object;
    /** @var string $objectType */
    public $objectType = 'CurrencyCalcItem';
    /** @var string $classKey */
    public $classKey = 'CurrencyCalcItem';
    /** @var array $languageTopics */
    public $languageTopics = array('currencycalc:default');
    /** @var string $permission */
    public $permission = 'save';
    /** @var CurrencyCalc $cc */
    protected $cc;

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->cc = $this->modx->getService('currencycalc', 'CurrencyCalc', MODX_CORE_PATH .
                                                                            'components/currencycalc/model/currencycalc/');
        $this->cc->initialize($this->modx->context->get('key'));

        return parent::initialize();
    }

    /**
     * We doing special check of permission
     * because of our objects is not an instances of modAccessibleObject
     * @return bool|string
     */
    public function beforeSave()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $id = (int)$this->getProperty('id');
        if (empty($id)) {
            return $this->modx->lexicon('currencycalc_err_ns');
        }

        $properties = array(
            'description' => $this->getProperty('description'),
            'active' => $this->getProperty('active', false),
        );
        $this->setProperties($properties);

        return parent::beforeSet();
    }
}

return 'CurrencyCalcItemUpdateProcessor';
