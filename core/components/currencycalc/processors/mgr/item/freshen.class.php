<?php

class CurrencyCalcItemFreshenProcessor extends modObjectProcessor
{
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
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            $q = $this->modx->newQuery('CurrencyCalcItem', array('active' => true));
            $q->select('id');
            if ($q->prepare() && $q->stmt->execute()) {
                $ids = $q->stmt->fetchAll(PDO::FETCH_COLUMN);
            }
        }
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('currencycalc_err_ns'));
        }

        foreach ($ids as $id) {
            /** @var CurrencyCalcItem $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('currencycalc_err_nf'));
            }
            if ($this->cc->refreshRate($object) !== true) {
                $errors = empty($errors) ? 1 : ++$errors;
                continue;
            }
            $object->save();
        }

        if (!empty($errors)) {
            if (count($ids) > 1) {
                return $this->failure($this->modx->lexicon('currencycalc_err_freshen_multiple', array(
                    'count' => $errors,
                    'total' => count($ids),
                )));
            } else {
                return $this->failure($this->modx->lexicon('currencycalc_err_freshen'));
            }
        }

        return $this->success();
    }
}

return 'CurrencyCalcItemFreshenProcessor';