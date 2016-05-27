@extends('app')

@section('content')

    <h1 class="page-header">Cores</h1>

    <div class="btn-group">
        <form method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <button type="submit" class="btn btn-primary btn-large btm-custom" >
                <i class="fa fa-refresh" aria-hidden="true"></i>Parse
            </button>
        </form>

        {{--<button href="#" class="btn btn-danger btn-large btm-custom disabled">--}}
            {{--<i class="fa fa-download" aria-hidden="true"></i>Impoart--}}
        {{--</button>--}}

    </div>
    <h2 class="sub-header">Section title</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>title</th>
                <th>event</th>
                <th>slot</th>
                <th>boostname</th>
                @foreach($cores[0]->levels as $key =>$levels)
                    <th>leavel {{++$key}}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
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
                    @foreach($core->levels as $level)
                        <td>{{$level}}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
