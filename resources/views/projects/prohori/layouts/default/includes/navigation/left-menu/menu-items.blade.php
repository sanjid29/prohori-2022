<?php

use App\Mainframe\Helpers\View;

$currentModuleName = isset($module) ? $module->name : '';
$breadcrumbs = isset($module) ? View::breadcrumb($module) : [];
?>

@include('projects.prohori.layouts.default.includes.navigation.left-menu.menu-items-admin')
