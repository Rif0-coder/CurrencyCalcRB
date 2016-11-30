CurrencyCalc.grid.Items = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'currencycalc-grid-items';
    }
    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/item/getlist',
            sort: 'rank',
            dir: 'ASC',
        },
        multi_select: true,
        // pageSize: MODx.config['default_per_page'],
        ddGroup: 'cc-items',
        ddAction: 'mgr/item/sort',
        enableDragDrop: true,
    });
    CurrencyCalc.grid.Items.superclass.constructor.call(this, config);

    // this.store.on('load', function () {
    //     console.log('Items store load this', this);
    // });
};
Ext.extend(CurrencyCalc.grid.Items, CurrencyCalc.grid.Default, {
    getFields: function () {
        return [
            'id',
            'rank',
            'source',
            'source_formatted',
            'from',
            'to',
            'rate',
            'updatedon_formatted',
            'description',
            'active',
            'actions',
        ];
    },

    getColumns: function () {
        return [{
            header: _('currencycalc_grid_id'),
            dataIndex: 'id',
            width: 70,
            sortable: true,
            fixed: true,
            resizable: false,
        }, {
            header: _('currencycalc_grid_source'),
            dataIndex: 'source_formatted',
            width: 150,
            sortable: true,
        }, {
            header: _('currencycalc_grid_from'),
            dataIndex: 'from',
            width: 100,
            sortable: true,
        }, {
            header: _('currencycalc_grid_to'),
            dataIndex: 'to',
            width: 100,
            sortable: true,
        }, {
            header: _('currencycalc_grid_rate'),
            dataIndex: 'rate',
            width: 100,
            sortable: true,
        }, {
            header: _('currencycalc_grid_updatedon'),
            dataIndex: 'updatedon_formatted',
            width: 150,
            sortable: false,
        }, {
            header: _('currencycalc_grid_description'),
            dataIndex: 'description',
            width: 400,
            sortable: false,
        }, {
            header: _('currencycalc_grid_active'),
            dataIndex: 'active',
            renderer: CurrencyCalc.utils.renderBoolean,
            sortable: true,
            fixed: true,
            resizable: false,
            width: 70,
        }, {
            header: _('currencycalc_grid_actions'),
            dataIndex: 'actions',
            id: 'actions',
            width: 170,
            sortable: false,
            fixed: true,
            resizable: false,
            renderer: CurrencyCalc.utils.renderActions,
        }];
    },

    getTopBar: function () {
        return [{
            text: '<i class="icon icon-money"></i>&nbsp; ' + _('currencycalc_button_item_create'),
            handler: this.createItem,
            cls: 'primary-button',
            scope: this,
        }, {
            text: '<i class="icon icon-refresh"></i>&nbsp; ' + _('currencycalc_button_item_freshen_all_active'),
            handler: this.freshenItem,
            scope: this,
        }, '->', this.getSearchField()];
    },

    getListeners: function () {
        return {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updateItem(grid, e, row);
            },
        };
    },

    createItem: function (btn, e) {
        var w = MODx.load({
            xtype: 'currencycalc-window-item-create',
            id: Ext.id(),
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    },
                    scope: this
                },
                failure: {
                    fn: function (r) {
                        if (typeof(r.a.result['message']) != 'undefined' && r.a.result['message'] != '') {
                            MODx.msg.alert(_('failure'), r.a.result['message']);
                        }
                    },
                    scope: this
                },
            },
        });
        w.reset();
        w.setValues({
            active: true,
        });
        w.show(e.target);
    },

    updateItem: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        } else if (!this.menu.record) {
            return false;
        }
        var id = this.menu.record.id;

        MODx.Ajax.request({
            url: this.config['url'],
            params: {
                action: 'mgr/item/get',
                id: id,
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = MODx.load({
                            xtype: 'currencycalc-window-item-update',
                            id: Ext.id(),
                            record: r,
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh();
                                    },
                                    scope: this
                                },
                                failure: {
                                    fn: function (r) {
                                        if (typeof(r.a.result['message']) != 'undefined' && r.a.result['message'] != '') {
                                            MODx.msg.alert(_('failure'), r.a.result['message']);
                                        }
                                    },
                                    scope: this
                                },
                            },
                        });
                        w.reset();
                        w.setValues(r['object']);
                        w.show(e.target);
                    }, scope: this
                }
            }
        });
    },

    actionItem: function (action, confirm, checkIds) {
        if (typeof(action) == 'undefined') {
            return false;
        }
        if (typeof(confirm) == 'undefined') {
            confirm = false;
        }
        if (typeof(checkIds) == 'undefined') {
            checkIds = true;
        }
        var ids = this._getSelectedIds();
        if (checkIds && !ids.length) {
            this.refresh();
            return false;
        }

        var params = {
            url: this.config['url'],
            params: {
                action: 'mgr/item/' + action,
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                },
                failure: {
                    fn: function (r) {
                        this.refresh();

                        if (typeof(r['message']) != 'undefined' && r['message'] != '') {
                            MODx.msg.alert(_('failure'), r['message']);
                        }
                    },
                    scope: this
                },
            },
        };

        if (confirm) {
            MODx.msg.confirm(Ext.apply({}, params, {
                title: ids.length > 1
                    ? _('currencycalc_button_' + action + '_multiple')
                    : _('currencycalc_button_' + action),
                text: _('currencycalc_confirm_' + action),
            }));
        } else {
            MODx.Ajax.request(params);
        }

        return true;
    },

    freshenItem: function () {
        this.loadMask.show();
        return this.actionItem('freshen', false, false);
    },

    enableItem: function () {
        return this.actionItem('enable');
    },

    disableItem: function () {
        return this.actionItem('disable');
    },

    removeItem: function () {
        return this.actionItem('remove', true);
    },
});
Ext.reg('currencycalc-grid-items', CurrencyCalc.grid.Items);