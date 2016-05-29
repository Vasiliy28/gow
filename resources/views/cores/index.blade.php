@extends('app')
@section('content')
    <h1 class="page-header">Cores</h1>


    @include('widgets._url_form', ['route_to_import' => 'import_cores'])

    <h2 class="sub-header">Section title</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th width="180px">title</th>
                <th width="100px">event</th>
                <th width="100px">slot</th>
                <th width="280px">boostname</th>

                @if($cores && !$cores->isEmpty())
                    @foreach($cores[0]->levels as $i => $level)
                        <th>level {{$i++}}</th>
                    @endforeach
                @endif
            </tr>
            </thead>
            <tbody> @if($cores && !$cores->isEmpty())
                @foreach($cores as $core)
                    <tr>
                        <td>{{$core->core_id}}</td>
                        <td>{{$core->title}}</td>
                        <td>{{$core->event}}</td>
                        <td>{{$core->slot}}</td>
                        <td>
                            <ul>
                                @foreach($core->boostname as $name)
                                    <li>{{$name}}</li>
                                @endforeach
                            </ul>
                        </td>
                        {{--@foreach($core->levels as $level)--}}
                        {{--<td>{{$level}}</td>--}}
                        {{--@endforeach--}}
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
@endsection
