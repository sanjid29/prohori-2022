<?php
/**
 * List of modules and their configs used for this project
 */
return [
    /*
    |--------------------------------------------------------------------------
    | Section: Set up Module Groups
    |--------------------------------------------------------------------------
    */
    "app-settings" => [
        "id" => 1,
        "uuid" => "770e22e8-e572-44a3-9a9a-be7fb1964ae5",
        "name" => "app-settings",
        "name_ext" => null,
        "slug" => null,
        "title" => "Settings",
        "description" => "Manage configuration",
        "route_path" => "app-settings",
        "route_name" => "app-settings",
        "parent_id" => 0,
        "level" => 0,
        "order" => 0,
        "default_route" => "app-settings.index",
        "color_css" => "aqua",
        "icon_css" => "fa fa-gears",
        "is_visible" => 1,
        "is_active" => 1,
    ],
    "accounts" => [
        "id" => 2,
        "uuid" => "a0dc562b-d6ce-45d1-9279-2a8ca2982dc8",
        "name" => "accounts",
        "name_ext" => null,
        "slug" => null,
        "title" => "Accounts",
        "description" => "Manage accounts",
        "route_path" => "accounts",
        "route_name" => "accounts",
        "parent_id" => 0,
        "level" => 0,
        "order" => 0,
        "default_route" => "accounts.index",
        "color_css" => "aqua",
        "icon_css" => "fa fa-calculator",
        "is_visible" => 0,
        "is_active" => 1,
    ],
];
