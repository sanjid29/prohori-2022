<?php
/**
 * @var \App\Module[] $modules
 * @var \App\User $user
 */
$modules = \App\Module::getActiveList();
?>
<li class="dropdown quick-modules-menu f-12">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-cubes"></i>
        <!-- <span class="label label-danger">9</span>-->
    </a>
    <ul class="dropdown-menu">
        <li class="dropdown-item">
            <input id="moduleSearchBox" name="module" class="form-control module-finder pull-right padding" type="text"
                   placeholder="Search a module"/>
            <div class="clearfix"></div>
        </li>
        <li class="dropdown-item">
            <ul id="moduleSearchBoxData" class="menu quick-module-list">
                @foreach($modules as $module)
                    @if(user()->hasAccess([$module->name.'-view-any']))
                        <li class="list-inline-item pull-left" style="width: 50%">
                            <a href="{{route($module->name.".index")}}">
                                {{$module->title}}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </li>
    </ul>
</li>


@section('js')
    @parent

    <script>
        $('#moduleSearchBox').bind('keydown keypress keyup change', function () {
            var search = this.value.toUpperCase();
            var $li = $("#moduleSearchBoxData li").hide();
            $li.filter(function () {
                return $(this).text().toUpperCase().indexOf(search) >= 0;
            }).show();
        });
    </script>
@endsection