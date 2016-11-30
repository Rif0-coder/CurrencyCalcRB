<?php

class ccTools
{
    /** @var modX $modx */
    protected $modx;
    /** @var CurrencyCalc $cc */
    protected $cc;
    /** @var array $config */
    public $config = array();

    /**
     * @param $modx
     * @param $config
     */
    public function __construct(modX &$modx, &$config)
    {
        $this->modx = &$modx;
        $this->config = &$config;

        $corePath = $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/currencycalc/';

        if (!is_object($this->modx->currencycalc) || !($this->modx->currencycalc instanceof CurrencyCalc)) {
            $this->cc = $this->modx->getService('currencycalc', 'currencycalc', $corePath . 'model/currencycalc/');
        } else {
            $this->cc = &$this->modx->currencycalc;
        }
    }

    /**
     * @param modProcessor $processor
     * @param array        $data
     * @param string       $default_lexicon
     *
     * @return bool
     */
    public function processorCheckRequired(modProcessor &$processor, array $data, $default_lexicon = '')
    {
        $hasError = false;

        if (is_array($data) && !empty($data)) {
            foreach ($data as $v) {
                $array = explode(':', $v);
                $key = $array[0];
                if (count($array) > 1) {
                    $lexicon = $array[1];
                } else {
                    $lexicon = $default_lexicon;
                }

                if (empty($processor->getProperty($key))) {
                    $hasError = true;
                    $processor->addFieldError($key, $this->modx->lexicon($lexicon));
                }
            }
        }

        return !$hasError;
    }

    /**
     * @param string       $class_key
     * @param int          $id
     * @param modProcessor $processor
     * @param array        $data
     * @param string       $default_lexicon
     * @param array        $condition_add
     *
     * @return bool
     */
    public function processorCheckUnique($class_key = '', $id = 0, modProcessor &$processor, array $data, $default_lexicon = '', array $condition_add = array())
    {
        $hasError = false;

        if (is_array($data) && !empty($data)) {
            $classKey = empty($class_key) ? $processor->classKey : $class_key;
            $id = (empty($id) && $id !== false) ? (int)$processor->getProperty('id') : $id;

            foreach ($data as $v) {
                $array = explode(':', $v);
                $key = $array[0];
                if (count($array) > 1) {
                    $lexicon = $array[1];
                } else {
                    $lexicon = $default_lexicon;
                }

                $condition = array(
                    $key => $processor->getProperty($key),
                );
                if (!empty($condition_add)) {
                    $condition = array_merge($condition, $condition_add);
                }
                if (!empty($id)) {
                    $condition['id:!='] = $id;
                }

                if ($this->modx->getCount($classKey, $condition)) {
                    $hasError = true;
                    $processor->addFieldError($key, $this->modx->lexicon($lexicon));
                }
            }
        }

        return !$hasError;
    }

    /**
     * Shorthand for original modX::invokeEvent() method with some useful additions.
     *
     * @param       $eventName
     * @param array $params
     * @param       $glue
     *
     * @return array
     */
    public function invokeEvent($eventName, array $params = array(), $glue = '<br/>')
    {
        if (isset($this->modx->event->returnedValues)) {
            $this->modx->event->returnedValues = null;
        }
        $response = $this->modx->invokeEvent($eventName, $params);
        if (is_array($response) && count($response) > 1) {
            foreach ($response as $k => $v) {
                if (empty($v)) {
                    unset($response[$k]);
                }
            }
        }
        $message = is_array($response) ? implode($glue, $response) : trim((string)$response);
        if (isset($this->modx->event->returnedValues) && is_array($this->modx->event->returnedValues)) {
            $params = array_merge($params, $this->modx->event->returnedValues);
        }

        return array(
            'success' => empty($message),
            'message' => $message,
            'data' => $params,
        );
    }

    /**
     * @param string $action
     * @param array  $data
     *
     * @return modProcessorResponse
     */
    public function runProcessor($action = '', $data = array())
    {
        $this->modx->error->reset();
        $processorsPath = !empty($this->cc->config['processorsPath']) ? $this->cc->config['processorsPath']
            : MODX_CORE_PATH;

        /* @var modProcessorResponse $response */
        $response = $this->modx->runProcessor($action, $data, array('processors_path' => $processorsPath));

        return $this->cc->config['prepareResponse'] ? $this->prepareResponse($response) : $response;
    }

    /**
     * This method returns prepared response
     *
     * @param mixed $response
     *
     * @return array|string $response
     */
    public function prepareResponse($response)
    {
        if ($response instanceof modProcessorResponse) {
            $output = $response->getResponse();
        } else {
            $message = $response;
            if (empty($message)) {
                $message = $this->modx->lexicon('err_unknown');
            }
            $output = $this->failure($message);
        }
        if ($this->cc->config['jsonResponse'] AND is_array($output)) {
            $output = $this->modx->toJSON($output);
        } elseif (!$this->cc->config['jsonResponse'] AND !is_array($output)) {
            $output = $this->modx->fromJSON($output);
        }

        return $output;
    }

    /**
     * More convenient error messages.
     *
     * @param modProcessorResponse $response
     * @param string               $glue
     *
     * @return string
     */
    public function formatProcessorErrors(modProcessorResponse $response, $glue = '<br>')
    {
        $errormsgs = array();

        if ($response->hasMessage()) {
            $errormsgs[] = $response->getMessage();
        }
        if ($response->hasFieldErrors()) {
            if ($errors = $response->getFieldErrors()) {
                foreach ($errors as $error) {
                    $errormsgs[] = $error->message;
                }
            }
        }

        return implode($glue, $errormsgs);
    }

    /**
     * Process and return the output from a Chunk by name.
     *
     * @param string $name       The name of the chunk.
     * @param array  $properties An associative array of properties to process the Chunk with, treated as placeholders
     *                           within the scope of the Element.
     * @param bool   $fastMode   If false, all MODX tags in chunk will be processed.
     *
     * @return string The processed output of the Chunk.
     */
    public function getChunk($name, array $properties = array(), $fastMode = false)
    {
        if (!$this->modx->parser) {
            $this->modx->getParser();
        }
        $pdoTools = $this->cc->getPdoTools();

        return $pdoTools->getChunk($name, $properties, $fastMode);
    }

    /**
     * Method for transform array to placeholders
     * @var array  $array With keys and values
     * @var string $prefix
     * @return array $array Two nested arrays With placeholders and values
     */
    public function makePlaceholders(array $array = array(), $prefix = '')
    {
        $result = array(
            'pl' => array(),
            'vl' => array(),
        );
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $result = array_merge_recursive($result, $this->makePlaceholders($v, $prefix . $k . '.'));
            } else {
                $result['pl'][$prefix . $k] = '[[+' . $prefix . $k . ']]';
                $result['vl'][$prefix . $k] = $v;
            }
        }

        return $result;
    }

    /**
     * @param string $message
     * @param array  $data
     * @param array  $placeholders
     *
     * @return array|string
     */
    public function failure($message = '', $data = array(), $placeholders = array())
    {
        $response = array(
            'success' => false,
            'message' => $this->modx->lexicon($message, $placeholders),
            'data' => $data,
        );

        return $this->cc->config['jsonResponse'] ? $this->modx->toJSON($response) : $response;
    }
}