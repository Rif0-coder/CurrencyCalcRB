<?php

class ccSourceCbr extends ccSourceBase
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

        if (@$xml->load('http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . date('d/m/Y'))) {
            $xmlRoot = $xml->documentElement;
            if ($valutes = $xmlRoot->getElementsByTagName('Valute')) {
                // Получаем значения курса
                foreach ($valutes as $xmlValute) {
                    $code = $xmlValute->getElementsByTagName('CharCode')->item(0)->nodeValue;
                    $code = strtoupper($code);

                    if (($from === 'RUB' && $code === $to) ||
                        ($to === 'RUB' && $code === $from) ||
                        ($from !== 'RUB' && $to !== 'RUB' && ($code === $from || $code === $to))
                    ) {
                        $nominal = $xmlValute->getElementsByTagName('Nominal')->item(0)->nodeValue;
                        $nominal = (float)str_replace(',', '.', $nominal);
                        $value = $xmlValute->getElementsByTagName('Value')->item(0)->nodeValue;
                        $value = (float)str_replace(',', '.', $value);

                        if ($from === 'RUB' && $code === $to) {
                            $fromVal = $nominal;
                            $toVal = $value;
                            break;
                        } elseif ($to === 'RUB' && $code === $from) {
                            $fromVal = $value;
                            $toVal = $nominal;
                            break;
                        } elseif ($from !== 'RUB' && $to !== 'RUB') {
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