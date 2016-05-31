@extends('app')
@section('content')
    <h1 class="page-header">Material</h1>

    {{--file_exists(public_path() . CoresController::FILE_PATH) ? CoresController::FILE_PATH : null--}}
    @include('widgets._url_form')

    <h2 class="sub-header">Materials:</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th>title</th>
                <th>event</th>
                <th>used</th>
            </tr>
            </thead>
            <tbody>

                @foreach($materials as $material)
                    <tr>
                        <td>{{$material->material_id}}</td>
                        <td>

                            {{$material->title}}
                            <img src="{{$material->images}}" >
                        </td>
                        <td>{{$material->event}}</td>
                        <td>
                                <ul>
                                    @foreach($material->used as $name)
                                        <li>{{$name}}</li>
                                    @endforeach
                                </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
