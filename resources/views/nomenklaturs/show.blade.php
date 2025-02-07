@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')
            @include('flash::message')

            <h4 id="section1" class="mg-b-20">Sektor/Bidang Nomenklatur Tahun {{ $nomenklatur->kode }}</h4>

            
            {!! $data !!}
            
            <div class="clearfix"></div>
            <hr>

            <div class="form-group col-sm-12">    
                <a class="btn btn-sm btn-light" href="{!! route('nomenklaturs.index') !!}"> <i class="fa fa-chevron-left"></i> Back </a>
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

        $(function(){
            $(document).on('click', '.table-del', function(e){
                let id = $(this).data('id');
                let url = $(this).data('link');
                let tahun = $(this).data('tahun');
                Swal.fire({
                    text: 'Yakin hapus data Sektor/Bidang?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#0168fa',
                    cancelButtonColor: '#dc3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // $("#table-form-"+id).submit();data-link
                        $.ajax({
                            method: "POST",
                            url: url,
                            data: { _token: "{!! csrf_token() !!}"},
                            beforeSend: function(){

                            },
                            success: function(res){
                                if(res.valid == true){
                                    Swal.fire({
                                        text: res.message,
                                        icon: 'success',
                                        confirmButtonText: 'OK',
                                        confirmButtonColor: '#0168fa',
                                    });
                                    $("#table-form-"+id).fadeOut().remove();
                                }
                                else{
                                    Swal.fire({
                                        text: res.message,
                                        icon: 'error',
                                        confirmButtonText: 'OK',
                                        confirmButtonColor: '#0168fa',
                                    });
                                }
                            }
                        })
                    }
                })
            });
        });
    </script>
@endsection