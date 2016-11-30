var CurrencyCalc = function (config) {
    config = config || {};
    CurrencyCalc.superclass.constructor.call(this, config);
};
Ext.extend(CurrencyCalc, Ext.Component, {
    page: {},
    window: {},
    grid: {},
    tree: {},
    panel: {},
    combo: {},
    config: {},
    view: {},
    utils: {},
    fields: {},
});
Ext.reg('currencycalc', CurrencyCalc);

CurrencyCalc = new CurrencyCalc();