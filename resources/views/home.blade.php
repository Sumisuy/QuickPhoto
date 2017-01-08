@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading col-xs-12">Image Uploader</div>

                <div class="panel-body col-lg-9 col-sm-8 col-xs-12">
                    <div id="file-uploader-dropbox" class="upload"></div>
                </div>
                <div id="file-uploader-list" class="panel-body col-lg-3 col-sm-4 col-xs-12">
                    <div id="image-list">
                        <ul>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
