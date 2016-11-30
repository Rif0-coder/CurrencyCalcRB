CurrencyCalc.fields.Item = function (config) {
    var data = config.record ? config.record.object : null;
    var r = {
        xtype: 'modx-tabs',
        border: true,
        autoHeight: true,
        style: {marginTop: '10px'},
        anchor: '100% 100%',
        items: [{
            title: _('currencycalc_tab_main'),
            layout: 'form',
            cls: 'modx-panel currencycalc-panel',
            autoHeight: true,
            items: [],
        }],
    };

    r.items[0].items.push({
        layout: 'column',
        border: false,
        style: {marginTop: '0px'},
        anchor: '100%',
        items: [{
            columnWidth: .5,
            layout: 'form',
            style: {marginRight: '5px'},
            items: [{
                layout: 'column',
                border: false,
                style: {marginTop: '0px'},
                anchor: '100%',
                items: [{
                    columnWidth: 1,
                    layout: 'form',
                    items: [{
                        xtype: 'currencycalc-combo-source',
                        id: config.id + '-source',
                        name: 'source',
                        fieldLabel: _('currencycalc_item_source'),
                        anchor: '100%',
                        // readOnly: data ? true : false,
                        disabled: data ? true : false,
                    }],
                }],
            }, {
                layout: 'column',
                border: false,
                style: {marginTop: '0px'},
                anchor: '100%',
                items: [{
                    columnWidth: .5,
                    layout: 'form',
                    style: {marginRight: '5px'},
                    items: [{
                        xtype: 'textfield',
                        id: config.id + '-from',
                        name: 'from',
                        fieldLabel: _('currencycalc_item_from'),
                        anchor: '100%',
                        // readOnly: data ? true : false,
                        disabled: data ? true : false,
                    }],
                }, {
                    columnWidth: .5,
                    layout: 'form',
                    labelWidth: 100,
                    style: {marginLeft: '5px'},
                    items: [{
                        xtype: 'textfield',
                        id: config.id + '-to',
                        name: 'to',
                        fieldLabel: _('currencycalc_item_to'),
                        anchor: '100%',
                        // readOnly: data ? true : false,
                        disabled: data ? true : false,
                    }],
                }],
            }],
        }, {
            columnWidth: .5,
            layout: 'form',
            labelWidth: 100,
            style: {marginLeft: '5px'},
            items: [{
                xtype: 'textarea',
                id: config.id + '-description',
                name: 'description',
                fieldLabel: _('currencycalc_item_description'),
                height: 102,
                anchor: '100%',
            }],
        }],
    }, {
        layout: 'column',
        border: false,
        style: {marginTop: '-10px'},
        anchor: '100%',
        items: [{
            columnWidth: 1,
            layout: 'form',
            items: [{
                xtype: 'xcheckbox',
                id: config.id + '-active',
                name: 'active',
                boxLabel: _('currencycalc_item_active'),
            }],
        }],
    });

    if (data) {
        r.items[0].items.push({
            xtype: 'hidden',
            id: config.id + '-id',
            name: 'id',
        });
    }

    return r;
};


CurrencyCalc.window.ItemCreate = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'currencycalc-window-item-create';
    }
    Ext.applyIf(config, {
        title: _('currencycalc_window_item_create'),
        baseParams: {
            action: 'mgr/item/create',
        },
        modal: true,
    });
    CurrencyCalc.window.ItemCreate.superclass.constructor.call(this, config);
};
Ext.extend(CurrencyCalc.window.ItemCreate, CurrencyCalc.window.Default, {
    getFields: function (config) {
        return CurrencyCalc.fields.Item(config);
    },
});
Ext.reg('currencycalc-window-item-create', CurrencyCalc.window.ItemCreate);


CurrencyCalc.window.ItemUpdate = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'currencycalc-window-item-update';
    }
    Ext.applyIf(config, {
        title: _('currencycalc_window_item_update'),
        baseParams: {
            action: 'mgr/item/update',
        },
        modal: true,
    });
    CurrencyCalc.window.ItemUpdate.superclass.constructor.call(this, config);
};
Ext.extend(CurrencyCalc.window.ItemUpdate, CurrencyCalc.window.Default, {
    getFields: function (config) {
        return CurrencyCalc.fields.Item(config);
    },
});
Ext.reg('currencycalc-window-item-update', CurrencyCalc.window.ItemUpdate);