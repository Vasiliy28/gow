<div class="row">
    <div class="col-sm-3">
        <form method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <button type="submit" class="btn btn-primary btn-large btm-custom" >
                <i class="fa fa-refresh" aria-hidden="true"></i>Parse
            </button>
        </form>
    </div>

    <div class="col-sm-3">
        <a download href="{{$file_path !== null ? $file_path : ""}}"class="{{$file_path == null ? "disabled" : ""}} btn btn-danger btn-large btm-custom ">
            <i class="fa fa-download" aria-hidden="true"></i>Impoart
        </a>
    </div>
</div>


