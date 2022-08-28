<?php
use App\Mainframe\Features\Form\Form as MfForm;use App\Mainframe\Features\Form\Upload as MfFormUpload;
/**
 * @var \App\Module $module
 * @var \App\Upload $upload
 * @var \App\Upload[] $uploads
 * @var \App\Mainframe\Features\Modular\BaseModule\BaseModule $element
 * @var \App\Mainframe\Features\Form\Upload $input
 */

$uploads = $uploads ?: [];
if (!isset($input)) {
    $var = MfForm::setUpVar($var ?? [], $errors ?? null, $element ?? null, $editable ?? null,
        $immutables ?? null, $hiddenFields ?? null);
    $input = $input ?? new MfFormUpload($var);
}
?>

<div class="row sortable {{optional($module)->name}}-file-list file-list">
    @foreach($uploads as $upload)
        <div class="col-md-6 col-sm-6 col-xs-12 filecard ">

            <input type="hidden" class="upload-id" name="upload-id[]" value="{{$upload->id}}"/>

            <div class="info-box shadow">

                <a href="{{$upload->downloadUrl()}}" title="{{$upload->name}}">
                    <span class="info-box-icon">
                        <img src="{{$upload->thumbnail()}}" alt="{{$upload->name}}" class="list-thumb"/>
                        <i class="fa fa-download download-overlay"></i>
                    </span>
                </a>

                <div class="info-box-content">

                    <span class="info-box-text">
                        <a href="{{ route('uploads.edit', $upload->id) }}"
                           title="{{$upload->name}}">{{$upload->name}}</a>
                    </span>

                    <span class="info-box-text text-sm file-info">
                        {{$upload->ext}} {{convertFileSize($upload->bytes)}} <br/>
                        {{ formatDateTime($upload->created_at) }}
                    </span>

                    <span class="info-box-number"></span>

                    <div class="pull-right">
                        @if($editable)
                            <?php
                            $var = [
                                'route' => route("uploads.destroy", $upload->id),
                                'redirect_success' => URL::full(),
                                'params' => [
                                    'class' => 'btn btn-xs btn-danger flat',
                                ],
                                'value' => '<i class="fa fa-trash"></i>',
                            ];
                            ?>
                            @include('form.delete-button',['var'=>$var])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="col-md-12">
        <button id="{{$input->uid}}-SaveSortBtn" class="btn-save-upload-order btn btn-danger" type="button"
                style="display: none">Save new order
        </button>
    </div>
</div>


@section('js')
    @parent
    @if(Route::has('uploads.reorder'))
        <script>
            var container = "#{{$input->uid}}";
            var reorderBtn = "#{{$input->uid}}-SaveSortBtn";


            $("#{{$input->uid}} > .sortable").on("sortupdate", function (event, ui) {
                // var arr = getInputAsArray(container + " .upload-id");
                // console.log(arr);
                $("#{{$input->uid}}-SaveSortBtn").show();
            });


            $("#{{$input->uid}}-SaveSortBtn").click(function () {
                $(this).prop('disabled', true);

                axios.post('{{route('uploads.reorder')}}', {
                    ids: getInputAsArray("#{{$input->uid}} .upload-id")
                }).then(response => {
                    showResponseModal(response.data, 3000);


                }).catch(error => {
                    console.log(error);
                }).then(() => {
                    // Re-activate UI
                    $("#{{$input->uid}}-SaveSortBtn").prop('disabled', false);
                });
            });

        </script>
    @endif
@endsection