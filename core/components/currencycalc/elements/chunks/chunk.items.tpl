<div class="currencycalc">
    {foreach $items as $c}
        <div class="currencycalc__item js-cc-item" data-cc-id="{$c['id']}">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="49%">
                        <div class="currencycalc__item_from">
                            <input type="text" class="currencycalc__item-input js-cc-input"
                                   data-cc-input-type="from" size="10" value="1">
                            <span class="currencycalc__item-currency">{$c['from']}</span>
                        </div>
                    </td>
                    <td width="2%">
                        <div class="currencycalc__item_equal">
                            =
                        </div>
                    </td>
                    <td align="right">
                        <div class="currencycalc__item_to">
                            <input type="text" class="currencycalc__item-input js-cc-input"
                                   data-cc-input-type="to" size="10" value="{$c['rate']}">
                            <span class="currencycalc__item-currency">{$c['to']}</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    {/foreach}

    <div class="currencycalc__last-update">
        Последнее обновление: {$last_update | date : 'd.m.Y H:i'}
    </div>
</div>

<style>
    .currencycalc__item {
        width: 50%;
        margin: 0 auto 10px;
    }

    .currencycalc__item-input {
        width: calc(100% - 70px);
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 0;
        padding: 4px 8px;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        outline: 0 none;
        font-size: 1.1em;
        color: #555;
    }

    .currencycalc__item-currency {
        display: inline-block;
        width: 30px;
    }

    .currencycalc__item_equal {
        font-size: 1.4em;
        font-weight: 100;
    }

    .currencycalc__last-update {
        color: #999;
        text-align: center;
    }
</style>