@extends('projects.default-project.layouts.centered.template')

@section('content')

    <h4>Password Reset</h4>

    <div class="card-body">

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                A password link has been sent to your email address.
            </div>
        @endif


        <form name="reset_password_form" method="POST" action="{{ route('password.email') }}"
              aria-label="{{ __('Reset Password') }}">
            @csrf

            @include('mainframe.form.text',['var'=>['name'=>'email','label'=>'Email', 'div'=>'col-sm-12 no-padding-r']])

            <div class="form-group row mb-0">
                <div class="col-md-12 offset-md-4">
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ __('Send Password Reset Link') }}
                    </button>
                </div>
            </div>
        </form>

        <div class="col-md-12 text-center" style="margin: 15px 0">
            <a href="{{ route('login') }}">
                {{ __('Already have account? Log in.') }}
            </a>
        </div>
        <div class="clearfix"></div>
    </div>

@endsection

@section('js')
    @parent
    <script>

        $("input[name=email]").addClass('validate[required]');

        showRequiredIcons();

        $('form[name=reset_password_form]').validationEngine({
            prettySelect: true,
            promptPosition: "topLeft",
            scroll: true
        });
    </script>

@endsection





