@extends('app')
@section('content')
    <h1 class="page-header">Buildings</h1>


    @include('widgets._url_form')

    <h2 class="sub-header">Buildings:</h2>
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
                @foreach($buildings as $building)
                    <tr>
                        <td>{{$building->id}}</td>
                        <td>{{$building->title }}
                            <img src="{{$building->images}}">
                        </td>
                        <td>{{$building->event}}</td>
                        <td>{{$building->slot}}</td>
                        <td>
                            <ul>
                                @foreach($building->boostname as $name)
                                    <li>{{$name}}</li>
                                @endforeach
                            </ul>
                        </td>
                        @foreach($building->levels as $level)
                            <?php $level = explode(",", $level)?>
                            <td>
                                <ul class="level">
                                    @foreach($level as $item)
                                        <li>{{$item}}</li>
                                    @endforeach
                                </ul>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
