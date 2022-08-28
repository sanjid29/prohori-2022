<?php
/**
 * @var \App\Mainframe\Features\Modular\BaseModule\BaseModule $element ;
 */
$var = \App\Mainframe\Features\Form\Form::setUpVar($var, $errors ?? null, $element ?? null, $editable ?? null,
    $immutables ?? null, $hiddenFields ?? null);
$plugin = new \App\Mainframe\Features\Form\Plugins\ListItems\ListItems($var);
?>

@if(isset($element) && $element->isCreated())

    <div class="clearfix"></div>
    @if($plugin->label)
        <h3>{!! $plugin->label !!}</h3>
    @endif
    @if($plugin->createLink())
        <a href="{!! $plugin->createLink() !!}" class="btn btn-default bg-white">
            {!! $plugin->createText() !!}
        </a>
    @endif
    <table id="{!! $plugin->tableId() !!}" class="table list-items-table {!! $plugin->tableClass() !!}">
        <thead>
        <tr>
            @if($plugin->showSerial())
                <th>SL.</th>
            @endif
            @foreach($plugin->titles() as $title)
                <th>{!!  $title !!}</th>
            @endforeach
            <th>-</th>
        </tr>

        </thead>
        <tbody>
        @foreach($plugin->items() as $row)
            <tr>
                @if($plugin->showSerial())
                    <td>{{ $loop->index+1 }}</td>
                @endif
                @foreach($plugin->fields() as $field)
                    @if($field == $plugin->linkColumn())
                        <td><a href="{{$row->editUrl()}}">{!! $row->$field !!}</a></td>
                    @else
                        <td>{!! $row->$field !!}</td>
                    @endif
                @endforeach
                <td class="text-right">
                    <?php
                    /** @var \App\District $row */
                    $var = [
                        'route' => $row->destroyUrl(),
                        'redirect_success' => URL::full(),
                        'name' => 'ListItemDelteBtn',
                        'class' => 'btn btn-default btn-xs',
                        'value' => '<i class="fa fa-trash"></i>',
                        'params' => [
                            'title' => 'Delete'
                        ],
                    ];
                    ?>
                    @if(user()->can('update',$element) && user()->can('delete',$row))
                        @include('form.delete-button',['var'=>$var])
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
@unset($var, $plugin)