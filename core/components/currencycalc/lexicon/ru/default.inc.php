<?php
include_once 'setting.inc.php';

$_lang['currencycalc'] = 'CurrencyCalc';
$_lang['currencycalc_menu_desc'] = 'Калькулятор валют';

// Табы
$_lang['currencycalc_tab_currencies'] = 'Валюты';
$_lang['currencycalc_tab_main'] = 'Основное';

// Названия столбцов
$_lang['currencycalc_grid_id'] = 'ID';
$_lang['currencycalc_grid_actions'] = 'Действия';
$_lang['currencycalc_grid_source'] = 'Источник';
$_lang['currencycalc_grid_from'] = 'Из';
$_lang['currencycalc_grid_to'] = 'В';
$_lang['currencycalc_grid_description'] = 'Описание';
$_lang['currencycalc_grid_active'] = 'Вкл';
$_lang['currencycalc_grid_updatedon'] = 'Обновлено';
$_lang['currencycalc_grid_rate'] = 'Курс';

// Название полей
$_lang['currencycalc_item_source'] = 'Источник';
$_lang['currencycalc_item_from'] = 'Из';
$_lang['currencycalc_item_to'] = 'В';
$_lang['currencycalc_item_description'] = 'Описание';
$_lang['currencycalc_item_active'] = 'Включено';

// Подтверждения
$_lang['currencycalc_confirm_remove'] = 'Вы уверены, что хотите удалить объект(-ы)?';

// Кнопки
$_lang['currencycalc_button_item_create'] = 'Добавить валюту';
$_lang['currencycalc_button_item_freshen_all_active'] = 'Освежить активные';
$_lang['currencycalc_button_update'] = 'Редактировать';
$_lang['currencycalc_button_freshen'] = 'Освежить';
$_lang['currencycalc_button_freshen_multiple'] = 'Освежить выбранное';
$_lang['currencycalc_button_enable'] = 'Включить';
$_lang['currencycalc_button_enable_multiple'] = 'Включить выбранное';
$_lang['currencycalc_button_disable'] = 'Выключить';
$_lang['currencycalc_button_disable_multiple'] = 'Выключить выбранное';
$_lang['currencycalc_button_remove'] = 'Удалить';
$_lang['currencycalc_button_remove_multiple'] = 'Удалить выбранное';

// Заголовки окон
$_lang['currencycalc_window_item_create'] = 'Добавить валюту';
$_lang['currencycalc_window_item_update'] = 'Редактировать валюту';

// Ошибки конкретные
$_lang['currencycalc_err_required_from'] = 'Необходимо указать основную валюту.';
$_lang['currencycalc_err_required_to'] = 'Необходимо указать второстепенную валюту.';
$_lang['currencycalc_err_unique_from_to'] = 'Такая валюта уже создана.';
$_lang['currencycalc_err_currencies_not_exists'] = 'Не найдено валюты для парсинга в источнике.';
$_lang['currencycalc_err_freshen'] = 'Не удалось достучаться до источника.';
$_lang['currencycalc_err_freshen_multiple'] = 'Не удалось достучаться до источника в [[+count]] случаев из [[+total]].';

// Ошибки общие
$_lang['currencycalc_err_required'] = 'Это поле необходимо заполнить.';
$_lang['currencycalc_err_unique'] = 'Это поле должно быть уникальным.';
$_lang['currencycalc_err_ns'] = 'Объект не указан.';
$_lang['currencycalc_err_nf'] = 'Объект не найден.';

// ComboBox
$_lang['currencycalc_combo_list_empty'] = 'Выпадающий список пуст...';

// Названия источников
$_lang['currencycalc_source_yahooapis'] = 'Yahoo Apis';
$_lang['currencycalc_source_cbr'] = 'ЦентроБанк РФ';
$_lang['currencycalc_source_nbkz'] = 'НацБанк Казахстана';
$_lang['currencycalc_source_freecurrencyratesapi'] = 'Free Currency Rates API';
$_lang['currencycalc_source_'] = '';

// Другое

//
//
//
//

$_lang['currencycalc_grid_actions'] = 'Действия';

$_lang['currencycalc_item_create'] = 'Создать предмет';
$_lang['currencycalc_item_update'] = 'Изменить Предмет';
$_lang['currencycalc_item_enable'] = 'Включить Предмет';
$_lang['currencycalc_items_enable'] = 'Включить Предметы';
$_lang['currencycalc_item_disable'] = 'Отключить Предмет';
$_lang['currencycalc_items_disable'] = 'Отключить Предметы';
$_lang['currencycalc_item_remove'] = 'Удалить Предмет';
$_lang['currencycalc_items_remove'] = 'Удалить Предметы';
$_lang['currencycalc_item_remove_confirm'] = 'Вы уверены, что хотите удалить этот Предмет?';
$_lang['currencycalc_items_remove_confirm'] = 'Вы уверены, что хотите удалить эти Предметы?';
$_lang['currencycalc_item_active'] = 'Включено';

$_lang['currencycalc_item_err_name'] = 'Вы должны указать имя Предмета.';
$_lang['currencycalc_item_err_ae'] = 'Предмет с таким именем уже существует.';
$_lang['currencycalc_item_err_nf'] = 'Предмет не найден.';
$_lang['currencycalc_item_err_ns'] = 'Предмет не указан.';
$_lang['currencycalc_item_err_remove'] = 'Ошибка при удалении Предмета.';
$_lang['currencycalc_item_err_save'] = 'Ошибка при сохранении Предмета.';
