<?php
/**
 * Скрипт запускающий задание на парсинг.
 * Запускается через консоль, крон или веб.
 * 0 2 * * * php /home_path/core/components/currencycalc/cron/freshen.php ids
 * Вместо "ids" указываем id валют, которые необходимо освежить. Можно несколько, через запятую. Можно не указывать,
 * тогда будет запущено всё активное.
 */

/** @var modX $modx */
/** @var CurrencyCalc $cc */

// Подключаем MODX
define('MODX_API_MODE', true);
do {
    $dir = dirname(!empty($file) ? dirname($file) : __FILE__);
    $file = $dir . '/index.php';
    $i = isset($i) ? --$i : 10;
} while ($i && !file_exists($file));
if (file_exists($file)) {
    require_once $file;
}
$modx->getService('error', 'error.modError');
$modx->getRequest();
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');
$modx->error->message = null;

$modx->lexicon->load('default');

if (XPDO_CLI_MODE) {
    $ids = @$argv[1];
} else {
    $ids = @$_REQUEST['ids'];
}
$ids = array_unique(array_diff(array_map('trim', explode(',', $ids)), array('')));

$cc = $modx->getService('currencycalc', 'CurrencyCalc', MODX_CORE_PATH . 'components/currencycalc/model/currencycalc/');
if (!is_object($cc)) {
    $modx->log(xPDO::LOG_LEVEL_ERROR, '[CurrencyCalc] Not exists CurrencyCalc class.');
}
$cc->initialize($modx->context->get('key'));

$params = array();
if (!empty($ids)) {
    $params = array('ids' => $modx->toJSON($ids));
}
$response = $cc->Tools->runProcessor('mgr/item/freshen', $params);
if (!$output = $cc->Tools->formatProcessorErrors($response)) {
    $output = $modx->lexicon('success');
}

if (!XPDO_CLI_MODE) {
    print '<pre>';
}
print_r($output);
if (!XPDO_CLI_MODE) {
    print '</pre>';
}
exit(PHP_EOL);