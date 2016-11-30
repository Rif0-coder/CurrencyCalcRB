<?php

class CurrencyCalcItemGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'CurrencyCalcItem';
    public $classKey = 'CurrencyCalcItem';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    //public $permission = 'list';

    /**
     * We do a special check of permissions
     * because our objects is not an instances of modAccessibleObject
     * @return boolean|string
     */
    public function beforeQuery()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }

    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = trim($this->getProperty('query'));
        if ($query) {
            $c->where(array(
                'from:LIKE' => "%{$query}%",
                'OR:to:LIKE' => "%{$query}%",
                'OR:description:LIKE' => "%{$query}%",
            ));
        }

        return $c;
    }

    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $array = $object->toArray();

        $array['source_formatted'] = $this->modx->lexicon('currencycalc_source_' . strtolower($array['source']));
        $array['updatedon_formatted'] = date('d.m.Y H:i:s', $array['updatedon']);

        $array['actions'] = array();
        $array['actions'][] = array(
            'cls' => '',
            'icon' => 'icon icon-refresh action-green',
            'title' => $this->modx->lexicon('currencycalc_button_freshen'),
            'multiple' => $this->modx->lexicon('currencycalc_button_freshen_multiple'),
            'action' => 'freshenItem',
            'button' => true,
            'menu' => true,
        );
        $array['actions'][] = '-';
        $array['actions'][] = array(
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('currencycalc_button_update'),
            'action' => 'updateItem',
            'button' => true,
            'menu' => true,
        );
        if (!$array['active']) {
            $array['actions'][] = array(
                'cls' => '',
                'icon' => 'icon icon-toggle-off action-green',
                'title' => $this->modx->lexicon('currencycalc_button_enable'),
                'multiple' => $this->modx->lexicon('currencycalc_button_enable_multiple'),
                'action' => 'enableItem',
                'button' => true,
                'menu' => true,
            );
        } else {
            $array['actions'][] = array(
                'cls' => '',
                'icon' => 'icon icon-toggle-off action-red',
                'title' => $this->modx->lexicon('currencycalc_button_disable'),
                'multiple' => $this->modx->lexicon('currencycalc_button_disable_multiple'),
                'action' => 'disableItem',
                'button' => true,
                'menu' => true,
            );
        }
        $array['actions'][] = array(
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('currencycalc_button_remove'),
            'multiple' => $this->modx->lexicon('currencycalc_button_remove_multiple'),
            'action' => 'removeItem',
            'button' => true,
            'menu' => true,
        );

        return $array;
    }
}

return 'CurrencyCalcItemGetListProcessor';