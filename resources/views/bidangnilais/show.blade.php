@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Hapus Data {{ ucfirst(strtolower($sektor)) }}</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <h4 class="mg-b-20">Hapus Data {{ ucfirst(strtolower($sektor)) }}</h4>
            @include('flash::message')
            
            <div class="forms">          
                <div class="form-group col-sm-12 col-lg-12">  
                    <a href="{!! route('data-'.strtolower($sektor).'.index') !!}" class="mg-t-20 btn btn-light"><i class="fas fa-chevron-left"></i> Back To List </a>        
                </div>
                
                <div class="form-group col-sm-2 col-lg-2">                              
                    {!! Form::label('tahun', 'Tahun Data:', ['class' => 'd-block']) !!}                            
                    {!! Form::select('tahun', $tahun, null,[
                            'class' => 'form-control select2',
                            'id' => 'tahun',
                            'required',
                    ]) !!}   
                </div> 
                <hr> 
                <div class="table-responsive">
                    <table class="table table-striped table-sektor table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th class="wd-50"></th>
                                <th>Sektor/ Bidang</th>
                                <th class="wd-50">#</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
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
        $(function(){
            $(document).on('click', '.del-row', function(e){
                let thos = $(this);
                let id = thos.data('id');
                let tahun = $("#tahun").val();                
                Swal.fire({
                    title: 'Yakin hapus data?',
                    text: 'Data akan terhapus dari sistem secara permanen',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#0168fa',
                    cancelButtonColor: '#dc3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url : "{!! route('data-'.$sektor.'.delete') !!}",
                            method: "post",
                            data: {
                                _token : '{!! csrf_token() !!}',
                                sektor : id,
                                tahun : tahun,
                            },
                            beforeSend: function(){
                                thos.prop('disabled', true).html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`);
                            },
                            success: function(result){    
                                if(result.valid == true){
                                    Swal.fire({
                                        text: result.message,
                                        icon: 'success',
                                        confirmButtonColor: '#0168fa'
                                    })
                                    $(".row_"+id).fadeOut().remove();
                                }   
                                else{
                                    Swal.fire({
                                        text: result.message,
                                        icon: 'warning',
                                        confirmButtonColor: '#0168fa'
                                    })
                                }      
                                thos.prop('disabled', false).html(`<i class="fas fa-trash"></i>`);          
                            },
                            error: function(res){
                                alert(res)
                            }
                        });
                    }
                })
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".select2").select2();
            $('#tahun').on('select2:select', function (e) {
                var data = e.params.data;
                $.ajax({
                    url : "{!! route('data-'.$sektor.'.getsektor') !!}",
                    data: {
                        tahun : data.id,
                    },
                    beforeSend: function(){
                        $("table.table-sektor>tbody").html('');
                    },
                    success: function(result){ 
                        $.each(result.sektor, function( index, value ) {
                            console.log($.inArray( value.id, result.nilai));
                            if($.inArray( value.id, result.nilai) >= 0){
                                html = `<tr class="`+value.cls_str+` row_`+value.id+`">
                                        <td>`;
                                if(value.child == true){
                                    html += `<button type="button" class="btn btn-outline-dark btn-icon btn-xs btn-collapse open" data-id="`+value.id+`"><i class="fas fa-chevron-down"></i></button>`;
                                }
                                html += `</td>  
                                        <td><span>`+value.code+` - `+value.name+`</span></td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-icon btn-xs del-row" data-id="`+value.id+`"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>`
                                $("table.table-sektor>tbody").append(html);
                            }
                        });
                    },
                    error: function(res){
                    }
                });
            });

            $(document).on('click', '.btn-collapse', function(){
                let thos = $(this);
                let id = thos.attr('data-id');
                if(thos.hasClass('open')){
                    thos.removeClass('open');
                    thos.html('<i class="fas fa-chevron-right"></i>');
                    $(".tr-"+id).hide();
                }
                else{
                    thos.addClass('open');
                    thos.html('<i class="fas fa-chevron-down"></i>');
                    $(".tr-"+id).show();
                }
            })
        });
    </script>
@endsection
