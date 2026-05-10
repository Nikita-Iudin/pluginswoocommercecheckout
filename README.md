# Custom Store Plugin

Плагин для WordPress/WooCommerce, позволяющий управлять полями оформления заказа прямо из админ-панели — без написания кода.

## Возможности

- Изменение названий (label) полей чекаута
- Изменение placeholder
- Изменение порядка полей (drag-and-drop)
- Скрытие/отображение полей
- Настройка обязательности (required/not required)
- Поддержка billing и shipping полей
- AJAX-сохранение с nonce-защитой
- Нативный интерфейс WordPress admin

## Установка

1. Скачайте архив или клонируйте репозиторий
2. Скопируйте папку `custom-store-plugin` в `/wp-content/plugins/`
3. Активируйте плагин в разделе **Плагины → Установленные**
4. Перейдите в **Custom Store → Checkout Fields** для настройки

## Требования

- WordPress 6.0+
- WooCommerce 8.0+
- PHP 8.0+

## Структура плагина

```
custom-store-plugin/
├── custom-store-plugin.php          Главный файл плагина
├── includes/
│   ├── core/
│   │   ├── class-loader.php         Реестр хуков (actions/filters)
│   │   └── class-security.php       Проверка nonce, прав, санитизация
│   ├── admin/
│   │   ├── class-admin-menu.php     Меню и подключение assets
│   │   ├── class-settings.php       Сохранение общих настроек (AJAX)
│   │   ├── class-checkout-settings.php  Сохранение/сброс полей (AJAX)
│   │   └── views/
│   │       ├── general-settings.php     Шаблон страницы «General»
│   │       └── checkout-fields-settings.php  Шаблон страницы «Checkout Fields»
│   ├── checkout/
│   │   └── class-checkout-fields.php    Фильтр woocommerce_checkout_fields
│   └── integrations/
│       ├── class-shipping.php       Заглушка — интеграции доставки
│       ├── class-payments.php       Заглушка — интеграции оплаты
│       └── class-api.php            Заглушка — REST API
├── assets/
│   ├── css/admin.css                Стили админ-интерфейса
│   └── js/admin.js                  Drag-and-drop + AJAX
├── languages/                       Файлы локализации
└── readme.txt                       Описание для WordPress.org
```

## Безопасность

Плагин следует лучшим практикам безопасности WordPress:

- **Защита прямого доступа** — `defined('ABSPATH')` в каждом PHP-файле
- **Проверка прав** — `current_user_can('manage_options')` на все admin-действия
- **Nonce-верификация** — `check_ajax_referer()` для всех AJAX-запросов
- **Санитизация** — `sanitize_text_field()`, `absint()`, `filter_var()` для входящих данных
- **Эскейпинг** — `esc_html()`, `esc_attr()`, `esc_url()` для вывода
- **Защита от CSRF/XSS** — nonce на все формы, санитизация входа + эскейпинг выхода

## Совместимость

- Классический чекаут WooCommerce (`[woocommerce_checkout]`) — полная поддержка
- Блочный чекаут (WooCommerce Blocks) — архитектура готова к будущей миграции
- Классические, гибридные и блочные темы WordPress
- Graceful degradation: если блок не поддерживает кастомизацию — плагин не вызывает фатальных ошибок

## Архитектура

Плагин построен по принципам OOP:

- **Singleton** — главный класс `Custom_Store_Plugin`
- **Loader** — централизованный реестр хуков
- **Разделение ответственности** — бизнес-логика отделена от UI
- **Модульность** — интеграции подключаются как отдельные классы-заглушки
- **Масштабируемость** — готово к добавлению CRM, ERP, вебхуков, REST API

## Лицензия

GPL-2.0+
