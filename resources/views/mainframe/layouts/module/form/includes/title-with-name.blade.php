<?php

/**
 * @var \App\Mainframe\Features\Modular\BaseModule\BaseModuleViewProcessor $view
 * @var \App\Module $module
 * @var \App\Mainframe\Features\Modular\BaseModule\BaseModule $element
 */
$name = ($element->name) ? ": ".optional($element)->name : optional($element)->name;
$prefix = null;

if ($element->isCreating()) {
    $prefix = "Create New ";
}
?>

@if($view->showFormCreateBtn() && $element->isUpdating())
    <a class="btn btn-xs module-create-btn {{$module->name.'-module-create-btn'}}"
       href="{{route("$module->name.create")}}" data-toggle="tooltip"
       title="Create a new {{Str::singular($module->title)}}"><i class="fa fa-plus"></i></a>
@endif

@if($view->showFormListBtn())
    <a class="btn btn-xs module-list-btn {{$module->name.'-module-list-btn'}}" href="{{route("$module->name.index")}}"
       data-toggle="tooltip"
       title="View list of {{Str::plural($module->title)}}"><i class="fa fa-list"></i></a>
@endif

<span class="form-title-prefix text-dark-gray">{{$prefix}} {{Str::singular($module->title)}} </span> {{$name}}

@if($view->showCloneBtn())
    @include('mainframe.layouts.default.includes.navigation.clone')
@endif