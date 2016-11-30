CurrencyCalc.combo.Search = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        xtype: 'twintrigger',
        ctCls: 'x-field-search',
        allowBlank: true,
        msgTarget: 'under',
        emptyText: _('search'),
        name: 'query',
        triggerAction: 'all',
        clearBtnCls: 'x-field-search-clear',
        searchBtnCls: 'x-field-search-go',
        onTrigger1Click: this._triggerSearch,
        onTrigger2Click: this._triggerClear,
    });
    CurrencyCalc.combo.Search.superclass.constructor.call(this, config);
    this.on('render', function () {
        this.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
            this._triggerSearch();
        }, this);
        this.positionEl.setStyle('margin-right', '1px');
    });
    this.addEvents('clear', 'search');
};
Ext.extend(CurrencyCalc.combo.Search, Ext.form.TwinTriggerField, {
    initComponent: function () {
        Ext.form.TwinTriggerField.superclass.initComponent.call(this);
        this.triggerConfig = {
            tag: 'span',
            cls: 'x-field-search-btns',
            cn: [
                {tag: 'div', cls: 'x-form-trigger ' + this.searchBtnCls},
                {tag: 'div', cls: 'x-form-trigger ' + this.clearBtnCls}
            ]
        };
    },
    _triggerSearch: function () {
        this.fireEvent('search', this);
    },
    _triggerClear: function () {
        this.fireEvent('clear', this);
    },
});
Ext.reg('currencycalc-combo-search', CurrencyCalc.combo.Search);
Ext.reg('currencycalc-field-search', CurrencyCalc.combo.Search);


CurrencyCalc.combo.Source = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        name: 'source',
        fieldLabel: config.name || 'source',
        hiddenName: config.name || 'source',
        displayField: 'display',
        valueField: 'value',
        fields: ['value', 'display'],
        url: CurrencyCalc.config['connector_url'],
        baseParams: {
            action: 'mgr/source/getlist',
        },
        pageSize: 20,
        typeAhead: false,
        editable: true,
        anchor: '100%',
        listEmptyText: '<div style="padding: 7px;">' + _('currencycalc_combo_list_empty') + '</div>',
        tpl: new Ext.XTemplate('\
            <tpl for="."><div class="x-combo-list-item currencycalc-combo__list-item">\
                <span>\
                    {display}\
                </span>\
            </div></tpl>',
            {compiled: true}
        ),
    });
    CurrencyCalc.combo.Source.superclass.constructor.call(this, config);
};
Ext.extend(CurrencyCalc.combo.Source, MODx.combo.ComboBox);
Ext.reg('currencycalc-combo-source', CurrencyCalc.combo.Source);