<?php

class CurrencyCalc
{
    /** @var modX $modx */
    public $modx;
    /** @var ccTools $Tools */
    public $Tools;
    /** @var pdoTools $pdoTools */
    public $pdoTools;
    /** @var array $initialized */
    public $initialized = array();

    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = array())
    {
        $this->modx = &$modx;

        $corePath = $this->modx->getOption('core_path') . 'components/currencycalc/';
        $assetsUrl = $this->modx->getOption('assets_url') . 'components/currencycalc/';

        $this->config = array_merge(array(
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $assetsUrl . 'connector.php',

            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'sourcesPath' => $corePath . 'model/currencycalc/sources/',
            'handlersPath' => $corePath . 'handlers/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'templatesPath' => $corePath . 'elements/templates/',
            'chunkSuffix' => '.chunk.tpl',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'processorsPath' => $corePath . 'processors/',

            'prepareResponse' => false,
            'jsonResponse' => false,
        ), $config);

        $this->modx->addPackage('currencycalc', $this->config['modelPath']);
        $this->modx->lexicon->load('currencycalc:default');
    }

    /**
     * @param string $ctx
     * @param array $sp
     *
     * @return boolean
     */
    public function initialize($ctx = 'web', $sp = array())
    {
        $this->config = array_merge($this->config, $sp, array('ctx' => $ctx));

        $this->getTools();
        $this->getPdoTools();
        $this->pdoTools->setConfig($this->config);

        if (!empty($this->initialized[$ctx])) {
            return true;
        }

        switch ($ctx) {
            case 'mgr':
                break;
            default:
                if (!defined('MODX_API_MODE') || !MODX_API_MODE) {
                    $config = $this->Tools->makePlaceholders($this->config);
                    // if ($css = trim($this->modx->getOption('currencycalc_frontend_css'))) {
                    //     $this->modx->regClientCSS(str_replace($config['pl'], $config['vl'], $css));
                    // }
                    if ($js = trim($this->modx->getOption('currencycalc_frontend_js'))) {
                        $this->modx->regClientScript(str_replace($config['pl'], $config['vl'], $js));
                    }
                }
                break;
        }

        $this->initialized[$ctx] = true;

        return true;
    }

    /**
     * @return ccTools
     */
    public function getTools()
    {
        if (!is_object($this->Tools)) {
            if ($class = $this->modx->loadClass('tools.ccTools', $this->config['handlersPath'], true, true)) {
                $this->Tools = new $class($this->modx, $this->config);
            }
        }

        return $this->Tools;
    }

    /**
     * @return pdoTools
     */
    public function getPdoTools()
    {
        if (!is_object($this->pdoTools) || !($this->pdoTools instanceof pdoTools)) {
            $this->pdoTools = $this->modx->getService('pdoFetch');
        }

        return $this->pdoTools;
    }

    /**
     * @param CurrencyCalcItem $object
     *
     * @return bool|float
     */
    public function refreshRate(CurrencyCalcItem $object)
    {
        $output = false;
        if ($source = $object->get('source')) {
            $className = 'ccSource' . $source;
            $this->modx->loadClass('ccSourceBase', $this->config['sourcesPath'], true, true);
            $this->modx->loadClass($className, $this->config['sourcesPath'], true, true);
            if (class_exists($className)) {
                $handler = new $className($this, $this->config);
                $output = $handler->run($object);
            }
        }

        return $output;
    }
}