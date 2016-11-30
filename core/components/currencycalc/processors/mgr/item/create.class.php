<?php

class CurrencyCalcItemCreateProcessor extends modObjectCreateProcessor
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
        $properties = $this->getProperties();
        foreach (array('from', 'to') as $v) {
            $properties[$v] = strtoupper($properties[$v]);
        }
        // return '<pre>' . print_r($this->getProperties(), 1) . '</pre>';

        // Проверяем на заполненность
        $required = array(
            'source',
            'from:currencycalc_err_required_from',
            'to:currencycalc_err_required_to',
        );
        $this->cc->Tools->processorCheckRequired($this, $required, 'currencycalc_err_required');

        // Проверяем на уникальность
        $unique = array(
            'to:currencycalc_err_unique_from_to',
        );
        $this->cc->Tools->processorCheckUnique('', 0, $this, $unique, 'currencycalc_err_unique', array(
            'source' => $properties['source'],
            'from' => $properties['from'],
        ));

        // Проверяем, есть ли такая комбинация валют на YahooApis
        if (!$this->hasErrors()) {
            $this->object->set('source', $properties['source']);
            $this->object->set('from', $properties['from']);
            $this->object->set('to', $properties['to']);
            if ($this->cc->refreshRate($this->object) !== true) {
                return $this->modx->lexicon('currencycalc_err_currencies_not_exists');
            }
        }

        $properties['rank'] = (1 + $this->modx->getCount($this->classKey));
        $this->setProperties($properties);

        // return '<pre>' . print_r($this->getProperties(), 1) . '</pre>';

        return parent::beforeSet();
    }
}

return 'CurrencyCalcItemCreateProcessor';