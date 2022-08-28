<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form name="confirmForm" id="confirmForm" method="POST" action="" accept-charset="UTF-8">
                <input name="_method" type="hidden" value="">
                <input name="_token" type="hidden" value="{{csrf_token()}}">
                <input name="redirect_success" type="hidden" value="{!! URL::full() !!}"/>
                <input name="redirect_fail" type="hidden" value="{!! URL::full() !!}"/>
                <input name="ret" type="hidden" value="json">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="confirmModalTitle">Confirm</h4>
                </div>

                <div class="modal-body" id="confirmModalBody">
                   Please confirm your action.
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" id='confirmSubmitBtn' name='confirm' class="btn btn-danger pull-right flat">
                            Confirm
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js')
    @parent
    <script>
        // Note: Enable mainframe validation framework
        // enableValidation('confirmForm');
    </script>
@endsection