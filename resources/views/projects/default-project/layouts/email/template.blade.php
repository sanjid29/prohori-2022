<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    @include('projects.default-project.layouts.email.includes.email-css')
    <title></title>
    @section('css')
        {{-- section: css --}}
    @show
</head>
<body>

<table class="content" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td class="header" width="100%" cellpadding="0" cellspacing="0">
            @section('title')
                {{-- section: title --}}
                {{-- @include('projects.default-project.layouts.email.includes.header')--}}
            @show
        </td>
    </tr>

    <tr>
        <td class="body" width="100%" cellpadding="0" cellspacing="0">
            <table class="inner-body" align="center" width="650px" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="content-cell">
                        @section('content-header')
                            <h1 style="text-align: center">{{env('APP_NAME')}}</h1>
                            {{-- section: content-header --}}
                        @show

                        @section('content')
                            {{-- section: content --}}
                        @show
                    </td>
                </tr>
                <tr>
                    <td class="content-cell footer" width="100%" cellpadding="0" cellspacing="0">
                        @include('projects.default-project.layouts.email.includes.footer')
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>