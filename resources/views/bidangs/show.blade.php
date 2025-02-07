@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sektor/Bidang {{ @$segment }}</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <h4 class="mg-b-10">Preview Sektor/Bidang {{ @$segment }}</h4>

            {!! $data !!}

            <div class="clearfix"></div>
            <hr>
            
            <!-- Submit Field -->
            <div class="form-group col-sm-12">    
                <a class="btn btn-sm btn-light" href="{!! route('sektor-bidang.'.strtolower($segment).'.index') !!}"> <i class="fa fa-chevron-left"></i> Back </a>
            </div>
        </div>
    </div>
@endsection


@section('styles')
    <style>
        /* Remove default bullets */
        ul, #myUL {
        list-style-type: none;
        }

        /* Remove margins and padding from the parent ul */
        #myUL {
        margin: 0;
        padding: 0;
        }

        /* Style the caret/arrow */
        .caret, .dott {
            cursor: pointer;
            user-select: none; /* Prevent text selection */
        }

        /* Create the caret/arrow with a unicode, and style it */
        .caret::before {
            content: "\25B6";
            color: black;
            display: inline-block;
            margin-right: 6px;
        }

        .dott::before{
            content: "\1f89E";
            color: black;
            display: inline-block;
            margin-right: 6px;
        }

        /* Rotate the caret/arrow icon when clicked on (using JavaScript) */
        .caret-down::before {
            transform: rotate(90deg);
        }

        /* Hide the nested list */
        .nested {
        display: none;
        }

        /* Show the nested list when the user clicks on the caret/arrow (with JavaScript) */
        .active {
        display: block;
        }

    </style>
@endsection

@section('scripts')
    <script>
        var toggler = document.getElementsByClassName("caret");
        var i;

        for (i = 0; i < toggler.length; i++) {
            toggler[i].addEventListener("click", function() {
                this.parentElement.querySelector(".nested").classList.toggle("active");
                this.classList.toggle("caret-down");
            });
        }
    </script>
@endsection
