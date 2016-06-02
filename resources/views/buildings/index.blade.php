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
                <th width="100px">images</th>

            </tr>
            </thead>
            <tbody>
                @foreach($buildings as $building)
                    <tr>
                        <td>{{$building->id}}</td>
                        <td>{{$building->title}}</td>
                        <td>
                            @foreach($building->images as $src)
                                <img src="{{$src}}" alt="">
                            @endforeach
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>
@endsection
