<div class="row">
    <div class="col-md-3 col-sm-6">
        <form method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <button type="submit" class="btn btn-primary btn-large btm-custom" >
                <i class="fa fa-refresh" aria-hidden="true"></i>Parse
            </button>
        </form>
    </div>

    <div class="col-md-3 col-sm-6">
        <a download href="{{isset($file_path) && $file_path ? $file_path : ""}}" class="{{isset($file_path) && $file_path  ? "" : "disabled"}} btn btn-danger btn-large btm-custom ">
            <i class="fa fa-download" aria-hidden="true"></i>Impoart
        </a>
    </div>
</div>


