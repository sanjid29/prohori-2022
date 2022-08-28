<?php
/**
 * @var \App\Mainframe\Modules\Modules\Module $module
 * @var \App\Mainframe\Modules\SuperHeroes\SuperHero $element
 */
$float = $float ?? true;
?>

@section('css')
    @parent
    @if(!$float)
        <style>
            .delete-cta {margin-right: 0;}

            .cta-block {position: relative;border-top: none;}
        </style>
    @endif
@endsection

<div class="clearfix"></div>

<div id="{{$module->name}}CtaBlock" class="cta-block no-margin col-md-12">

    {{--  Save button --}}
    @if(((isset($element) && $editable)) || (!isset($element) && $user->can('create',$element)))

        <input name="redirect_success" type="hidden" class="redirect-success {{$module->name}}-redirect-success"
               id="{{$module->name}}-redirect-success"
               value="{{ request('redirect_success',route($module->name.".index") ) }}"/>

        <input name="redirect_fail" type="hidden" class="redirect_fail {{$module->name}}-redirect-fail"
               id="{{$module->name}}-redirect-fail"
               value="{{ request('redirect_fail',URL::full())  }}"/>

        <input name="ret" type="hidden" class="ret {{$module->name}}-ret"
               id="{{$module->name}}-ret"
               value="{{Request::get('ret')}}"/>

        <button id="{{$module->name}}SubmitBtn" type="submit"
                class="submit btn btn-success {{$module->name}}-SubmitBtn module-save-btn">
            <i class="fa fa-check"></i> &nbsp;&nbsp;&nbsp;Save
        </button>

    @endif

    {{-- Delete modal open button--}}
    @if($element->isCreated() && user()->can('delete',$element))
        <div class="pull-right delete-cta no-padding">
            <?php
            $var = [
                'route' => route($module->name.".destroy", $element->id),
                'redirect_success' => route($module->name.".index"),
            ];
            ?>
            @include('form.delete-button',['var'=>$var])
        </div>
    @endif

    {{--  Change log button      --}}
    @if($element->isCreated())
        <a target="_blank" class="btn  bg-white pull-right"
           href="{{route("$module->name.changes",$element->id)}}">Change log</a>
    @endif
</div>