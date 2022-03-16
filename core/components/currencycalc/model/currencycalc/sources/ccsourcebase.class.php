<?php

abstract class ccSourceBase
{
    /** @var modX $modx */
    public $modx;
    /** @var CurrencyCalc $cc */
    public $cc;
    /** @var array $config */
    public $config;

    /**
     * @param CurrencyCalc $cc
     * @param array $config
     */
    function __construct(CurrencyCalc &$cc, array $config = array())
    {
        $this->cc = &$cc;
        $this->modx = &$cc->modx;
        $this->config = array_merge(array(), $config);
        $this->modx->lexicon->load('currencycalc:default');
    }

    /**
     * @param CurrencyCalcItem $object
     *
     * @return bool|float
     */
    abstract public function run(CurrencyCalcItem $object);
}