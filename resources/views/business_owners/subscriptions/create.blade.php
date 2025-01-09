@extends('layouts.owner')

@section('content')
    <div class="row new-chemist-parent-row">
        <script src="https://www.paytr.com/js/iframeResizer.min.js"></script>
        <iframe src="https://www.paytr.com/odeme/guvenli/<?php echo $token; ?>" id="paytriframe" frameborder="0" scrolling="no"
            style="width: 100%;"></iframe>
        <script>
            iFrameResize({}, '#paytriframe');
        </script>
    </div>
@endsection
