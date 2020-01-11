@extends('layouts.app')

@section('content')
<div class="container">
    <div class=" d-flexcol-md-12 justify-content-between">
        <h3 class="mt-5">Possible Tiles: <button id="toggle" class="btn btn-primary">Manual Input toggle</button></h3>
    </div>
    <div id="manual" class="row entry justify-content-center" style="display:none">
        <small></small>
        <form action="/" enctype="multipart/form-data" method="post">
            @csrf
            <div class="row">
                <div class="col-xs-12 colorContainer">
                    <div id="inputContainer" class="col-xs-10">
                        <div class="form-group row">
                            <label for="title" class="col-xs-12 col-form-label font-weight-bold"><strong>{{ __('Tile Colors: ') }}</strong></label>
                            <input id="title" type="text" class="form-control" name="tiles[]" placeholder="blue,red">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pt-4">
                <button id="row" class="btn btn-primary d-flex mr-3">Add Row</button>
                <button type="submit" class="btn btn-primary d-flex">Generate Solution</button>
            </div>
        </form>
    </div>
    <div id="tiles" class="row justify-content-left entry">
        @foreach($tiles as $tile)
            <div class="col-md-2 d-flex tile" style="width:100%; height:80px;">
                <div class="col-xs-6 {{ $tile[0] }}" style="width:100%; height:100%;"></div>
                <div class="col-xs-6  {{ $tile[1] }}" style="width:100%; height:100%;"></div>
            </div>
        @endforeach
    </div>
    <h3 class="mt-5 entry">Solution: </h3>
    <div class="row justify-content-left entry">
        <div class="rounded-circle blue col-md-1 mr-5" style="height:80px; width:100%"></div>
        @forelse($solution as $tile)
            <div class="col-md-2 col-xs-6 d-flex tile" style="width:100%; height:80px;">
                <div class="col-xs-3 {{ $tile[0] }}" style="width:100%; height:100%;"></div>
                <div class="col-xs-3  {{ $tile[1] }}" style="width:100%; height:100%;"></div>
            </div>
        @empty
            <div class="col-md-4">
                <div class="alert alert-danger">
                No solutions found. Please try again.
                </div>
            </div>
        @endforelse
        <div class="rounded-circle green col-md-1 ml-5"  style="height:80px; width:100%"></div>
    </div>
</div>
@endsection

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script>
    $('document').ready(function(){
        $('#toggle').click(function(){
            $('.entry').toggle();
        });

        $('#row').click(function(e){
            e.preventDefault();
            let divElement = $('#inputContainer').clone();
            $('.colorContainer').append(divElement);
            $('.form-control').last().val('').focus();
        })
    });
</script>
