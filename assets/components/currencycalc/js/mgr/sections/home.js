CurrencyCalc.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'currencycalc-panel-home',
            renderTo: 'currencycalc-panel-home-div'
        }]
    });
    CurrencyCalc.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(CurrencyCalc.page.Home, MODx.Component);
Ext.reg('currencycalc-page-home', CurrencyCalc.page.Home);