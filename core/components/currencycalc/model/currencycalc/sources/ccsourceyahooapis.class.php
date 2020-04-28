<?php

class ccSourceYahooApis extends ccSourceBase
{
    /** @var string $paramRate */
    protected $paramRate = 'LastTradePriceOnly';

    /**
     * @param CurrencyCalcItem $object
     *
     * @return bool|float
     */
    public function run(CurrencyCalcItem $object)
    {
        $output = false;
        $from = $object->get('from');
        $to = $object->get('to');

        /** @var modRestCurlClient $curl */
        $curl = $this->modx->getService('rest.modRestCurlClient');
        $result = $this->modx->fromJSON($curl->request('https://query.yahooapis.com/v1/public/yql', '', 'GET', array(
            'q' => 'select * from yahoo.finance.quote where symbol in ("' . $from . $to . '=X")',
            'env' => 'store://datatables.org/alltableswithkeys',
            'format' => 'json',
        )));
        if (!empty($result['query']['results']['quote']['Name'])) {
            $output = true;
            $rate = (float)$result['query']['results']['quote'][$this->paramRate];
            $object->set('rate', $rate);
            $object->set('updatedon', time());
        }

        return $output;
    }
}