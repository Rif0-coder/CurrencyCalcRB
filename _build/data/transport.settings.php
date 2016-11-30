<?php
/** @var modX $modx */
/** @var array $sources */

$settings = array();

$tmp = array(
    'frontend_js' => array(
        'xtype' => 'textfield',
        'area' => 'seofilter_main',
        'value' => '[[+jsUrl]]web/default.js',
    ),
);

foreach ($tmp as $k => $v) {
    /** @var modSystemSetting $setting */
    $setting = $modx->newObject('modSystemSetting');
    $setting->fromArray(array_merge(array(
        'key' => 'currencycalc_' . $k,
        'namespace' => PKG_NAME_LOWER,
    ), $v), '', true, true);

    $settings[] = $setting;
}
unset($tmp);

return $settings;
