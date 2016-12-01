<?php

class ccSourceNbKz extends ccSourceBase
{
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
        $fromVal = 0;
        $toVal = 0;

        $xml = new DOMDocument();
        if (@$xml->load('http://www.nationalbank.kz/rss/rates_all.xml')) {
            $xmlRoot = $xml->documentElement;
            if ($valutes = $xmlRoot->getElementsByTagName('item')) {
                // Получаем значения курса
                foreach ($valutes as $xmlValute) {
                    $code = $xmlValute->getElementsByTagName('title')->item(0)->nodeValue;
                    $code = strtoupper($code);

                    if (($from === 'KZT' && $code === $to) ||
                        ($to === 'KZT' && $code === $from) ||
                        ($from !== 'KZT' && $to !== 'KZT' && ($code === $from || $code === $to))
                    ) {
                        $nominal = $xmlValute->getElementsByTagName('quant')->item(0)->nodeValue;
                        $nominal = (float)str_replace(',', '.', $nominal);
                        $value = $xmlValute->getElementsByTagName('description')->item(0)->nodeValue;
                        $value = (float)str_replace(',', '.', $value);

                        if ($from === 'KZT' && $code === $to) {
                            $fromVal = $nominal;
                            $toVal = $value;
                            break;
                        } elseif ($to === 'KZT' && $code === $from) {
                            $fromVal = $value;
                            $toVal = $nominal;
                            break;
                        } elseif ($from !== 'KZT' && $to !== 'KZT') {
                            if ($code === $from) {
                                $fromVal = $value / $nominal;
                            } elseif ($code === $to) {
                                $toVal = $value / $nominal;
                            }
                        }
                    }
                }

                // Вычисляем соотношение
                if (!empty($fromVal) && !empty($toVal)) {
                    $output = true;

                    $rate = (float)($fromVal / $toVal);
                    $object->set('rate', $rate);
                    $object->set('updatedon', time());
                }
            }
        }

        return $output;
    }
}