CurrencyCalc.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('currencycalc') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('currencycalc_tab_currencies'),
                layout: 'anchor',
                items: [{
                    xtype: 'currencycalc-grid-items',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    CurrencyCalc.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(CurrencyCalc.panel.Home, MODx.Panel);
Ext.reg('currencycalc-panel-home', CurrencyCalc.panel.Home);
