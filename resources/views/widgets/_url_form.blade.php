

<div class="btn-group">
    <form method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <button type="submit" class="btn btn-primary btn-large btm-custom" >
            <i class="fa fa-refresh" aria-hidden="true"></i>Parse
        </button>
    </form>

    <a href="{{route($route_to_import)}}" target="_blank" class="btn btn-danger btn-large btm-custom ">
        <i class="fa fa-download" aria-hidden="true"></i>Impoart
    </a>
</div>