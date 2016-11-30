<?php
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
}
else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var CurrencyCalc $CurrencyCalc */
$CurrencyCalc = $modx->getService('currencycalc', 'CurrencyCalc', $modx->getOption('currencycalc_core_path', null,
        $modx->getOption('core_path') . 'components/currencycalc/') . 'model/currencycalc/'
);
$modx->lexicon->load('currencycalc:default');

// handle request
$corePath = $modx->getOption('currencycalc_core_path', null, $modx->getOption('core_path') . 'components/currencycalc/');
$path = $modx->getOption('processorsPath', $CurrencyCalc->config, $corePath . 'processors/');
$modx->getRequest();

/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));