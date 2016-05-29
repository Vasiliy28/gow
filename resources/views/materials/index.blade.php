@extends('app')
@section('content')
    <h1 class="page-header">Material</h1>

    {{--file_exists(public_path() . CoresController::FILE_PATH) ? CoresController::FILE_PATH : null--}}
    @include('widgets._url_form', ['file_path' => null ])

    <h2 class="sub-header">Materials:</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th width="180px">title</th>
                <th width="100px">event</th>
                <th width="100px">slot</th>
                <th width="270px">boostname</th>

            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
@endsection
