<?php

class CurrencyCalcSourceGetListProcessor extends modProcessor
{
    /** @var CurrencyCalc $cc */
    protected $cc;

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->cc = $this->modx->getService('currencycalc', 'CurrencyCalc', MODX_CORE_PATH . 'components/currencycalc/model/currencycalc/');
        $this->cc->initialize($this->modx->context->get('key'));

        return parent::initialize();
    }

    /**
     * @return string
     */
    public function process()
    {
        $output = array();
        $sources = array(
            // 'YahooApis',
            'Cbr',
            'NbKz',
            'FreeCurrencyRatesApi',
        );
        foreach ($sources as $source) {
            $output[] = array(
                'value' => $source,
                'display' => $this->modx->lexicon('currencycalc_source_' . strtolower($source)),
            );
        }

        return $this->outputArray($output);
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array('currencycalc:default');
    }
}

return 'CurrencyCalcSourceGetListProcessor';