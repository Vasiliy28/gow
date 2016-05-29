@extends('app')

@section('content')

    <h1 class="page-header">Piece</h1>

    @include('widgets._url_form', ['file_path' => file_exists(public_path() . PiecesController::FILE_PATH) ? PiecesController::FILE_PATH : null])

    <h2 class="sub-header">Pieces :</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th width="180px">title</th>
                <th width="100px">event</th>
                <th width="270px">boostname</th>

                @if($pieces && !$pieces->isEmpty())
                    @foreach($pieces[0]->levels as $i => $level)
                        <th width="130px">level {{$i++}}</th>
                    @endforeach
                @endif

            </tr>
            </thead>
            <tbody>
            @if($pieces && !$pieces->isEmpty())
                @foreach($pieces as $piece)
                    <tr>
                        <td>{{$piece->piece_id}}</td>
                        <td>{{$piece->title }}
                        <img src="{{$piece->images}}">
                        </td>
                        <td>{{$piece->event}}</td>
                        <td>
                            <ul>
                                @foreach($piece->boostname as $name)
                                    <li>{{$name}}</li>
                                @endforeach
                            </ul>
                        </td>
                        @foreach($piece->levels as $level)
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
            @endif
            </tbody>
        </table>
    </div>

@endsection
