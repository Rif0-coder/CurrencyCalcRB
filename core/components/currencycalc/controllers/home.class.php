<?php

class CurrencyCalcHomeManagerController extends modExtraManagerController
{
    /** @var CurrencyCalc $CurrencyCalc */
    public $CurrencyCalc;

    /**
     *
     */
    public function initialize()
    {
        $path = $this->modx->getOption('currencycalc_core_path', null, $this->modx->getOption('core_path') . 'components/currencycalc/') . 'model/currencycalc/';
        $this->CurrencyCalc = $this->modx->getService('currencycalc', 'CurrencyCalc', $path);
        parent::initialize();
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array('currencycalc:default');
    }

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }

    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('currencycalc');
    }

    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->CurrencyCalc->config['cssUrl'] . 'mgr/main.css');
        $this->addCss($this->CurrencyCalc->config['cssUrl'] . 'mgr/bootstrap.buttons.css');

        $this->addJavascript($this->CurrencyCalc->config['jsUrl'] . 'mgr/currencycalc.js');

        $this->addJavascript($this->CurrencyCalc->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->CurrencyCalc->config['jsUrl'] . 'mgr/misc/combo.js');

        $this->addJavascript($this->CurrencyCalc->config['jsUrl'] . 'mgr/misc/default.grid.js');
        $this->addJavascript($this->CurrencyCalc->config['jsUrl'] . 'mgr/misc/default.window.js');
        
        $this->addJavascript($this->CurrencyCalc->config['jsUrl'] . 'mgr/widgets/items.grid.js');
        $this->addJavascript($this->CurrencyCalc->config['jsUrl'] . 'mgr/widgets/items.window.js');

        $this->addJavascript($this->CurrencyCalc->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->CurrencyCalc->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        CurrencyCalc.config = ' . json_encode($this->CurrencyCalc->config) . ';
        CurrencyCalc.config[\'connector_url\'] = "' . $this->CurrencyCalc->config['connectorUrl'] . '";
        Ext.onReady(function() {
            MODx.load({
                xtype: "currencycalc-page-home",
            });
        });
        </script>
        ');
    }

    /**
     * @return string
     */
    public function getTemplateFile()
    {
        return $this->CurrencyCalc->config['templatesPath'] . 'home.tpl';
    }
}