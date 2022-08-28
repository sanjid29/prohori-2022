<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title">
                <a data-toggle="collapse" href="#other_info">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Token
                </a>
            </h5>
        </div>
        <div id="other_info" class="panel-collapse collapse" style="margin:15px 0;">
            <div class="col-md-12">
                <div class="col-md-12 no-padding">
                    {{--auth_token--}}
                    @include('form.text',['var'=>['name'=>'api_token','label'=>'API token', 'div'=>'col-sm-8']])
                    <button id="api_token_generate" name="api_token_generate" class="btn btn-default"
                            style="margin-top: 20px">
                        <i class="fa fa-refresh"></i> Refresh
                    </button>
                </div>
                {{--auth_token--}}
                @include('form.plain-text',['var'=>['name'=>'auth_token','label'=>'Auth token', 'div'=>'col-sm-6']])
                {{--api_token_generated_at--}}
                @include('form.datetime',['var'=>['name'=>'api_token_generated_at','label'=>'Api token generated at','editable'=>false]])
                {{--device_name--}}
                @include('form.plain-text',['var'=>['name'=>'device_token','label'=>'Device token', 'div'=>'col-sm-12']])
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>


@section('js')
    @parent
    <script>
        //Make the api_token readonly
        $("input[name=api_token]").attr('readonly', true);

        $("#api_token_generate").click(function (e) {
            event.preventDefault(e);
            $("input[name=api_token]").val(randomString(64));
            console.log(randomString(64));
        });
    </script>
@endsection
