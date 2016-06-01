@extends('app')
@section('content')
    <h1 class="page-header">Core</h1>


    @include('widgets._url_form')

    <h2 class="sub-header">Cores:</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th width="180px">title</th>
                <th width="100px">event</th>
                <th width="100px">slot</th>
                <th width="270px">boostname</th>

                @if(isset($cores[0]))
                    @foreach($cores[0]->levels as $i => $level)
                        <th width="130px">level {{$i++}}</th>
                    @endforeach
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($cores as $core)
                <tr>
                    <td>{{$core->id}}</td>
                    <td>{{$core->title }}
                        @foreach($core->images as $image)
                            <img src="{{$image}}">
                        @endforeach
                    </td>
                    <td>{{$core->event}}</td>
                    <td>{{$core->slot}}</td>
                    <td>
                        <ul>
                            @foreach($core->boostname as $name)
                                <li>{{$name}}</li>
                            @endforeach
                        </ul>
                    </td>
                    @foreach($core->levels as $level)
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
