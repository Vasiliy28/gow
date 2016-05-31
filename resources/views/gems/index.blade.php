@extends('app')
@section('content')
    <h1 class="page-header">Gem</h1>

    @include('widgets._url_form')

    <h2 class="sub-header">Gems:</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th width="180px">title</th>
                <th width="100px">event</th>
                <th width="100px">4th Gem Slot</th>
                <th width="270px">boostname</th>

                @foreach($gems[0]->levels as $i => $level)
                    <th width="130px">level {{$i++}}</th>
                @endforeach

            </tr>
            </thead>
            <tbody>
                @foreach($gems as $gem)
                    <tr>
                        <td>{{$gem->id}}</td>
                        <td>{{$gem->title }}
                            <img src="{{$gem->images}}">
                        </td>
                        <td>{{$gem->event}}</td>
                        <td>{{$gem->four_th_slot ? "Yes" : "No"}}</td>
                        <td>
                            <ul>
                                @foreach($gem->boostname as $name)
                                    <li>{{$name}}</li>
                                @endforeach
                            </ul>
                        </td>

                        @foreach($gem->levels as $key => $level)
                            <?php $level = explode(",", $level)?>
                        <td>
                            <ul class="level">
                                @foreach($level as $item)
                                    <li>{{$item}}</li>
                                @endforeach
                            </ul>
                            <img src="{{$gem->gallery[$key]}}">
                        </td>
                        @endforeach

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
