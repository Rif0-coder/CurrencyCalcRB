<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var CurrencyCalc $cc */

$sp = &$scriptProperties;
$cc = $modx->getService('currencycalc', 'CurrencyCalc', MODX_CORE_PATH . 'components/currencycalc/model/currencycalc/');
if (!is_object($cc)) {
    return 'Not exists CurrencyCalc class.';
}
$cc->initialize($modx->context->get('key'));

//
$sp['tpl'] = $modx->getOption('tpl', $sp, 'tpl.CurrencyCalc');
$sp['where'] = $modx->getOption('where', $sp, '[]');
$sp['sortby'] = $modx->getOption('sortby', $sp, 'rank');
$sp['sortdir'] = $modx->getOption('sortbir', $sp, 'ASC');
$sp['toPlaceholder'] = $modx->getOption('toPlaceholder', $sp, false);

foreach (array('where') as $v) {
    if (isset($sp[$v])) {
        if (!is_array($sp[$v])) {
            $sp[$v] = $modx->fromJSON($sp[$v]);
        }
        if (!is_array($sp[$v])) {
            $sp[$v] = array();
        }
    }
}

//
$items = array();
$q = $modx->newQuery('CurrencyCalcItem')
    ->select($modx->getSelectColumns('CurrencyCalcItem'))
    ->where(array_merge(array('active' => true), $sp['where']))
    ->sortby($sp['sortby'], $sp['sortdir']);
$tmp = $modx->getIterator('CurrencyCalcItem', $q);
/** @var CurrencyCalcItem $item */
foreach ($tmp as $item) {
    $items[] = $item->toArray();
}
unset($tmp);

//
$last_update = 0;
foreach ($items as $item) {
    if ($item['updatedon'] > $last_update) {
        $last_update = $item['updatedon'];
    }
}

//
$output = $cc->Tools->getChunk($sp['tpl'], array(
    'items' => $items,
    'last_update' => $last_update,
));

//
if (!empty($sp['toPlaceholder'])) {
    $modx->setPlaceholder($sp['toPlaceholder'], $output);
    $output = '';
}

//
$js_data = array();
foreach ($items as $item) {
    unset($item['description'], $item['updatedon'], $item['active'], $item['properties']);
    $js_data[$item['id']] = $item;
}
$modx->regClientScript('<script>
    var CurrencyCalc = new CurrencyCalc({
        data: ' . ($modx->toJSON($js_data)) . ',
    });
</script>', true);

return $output;