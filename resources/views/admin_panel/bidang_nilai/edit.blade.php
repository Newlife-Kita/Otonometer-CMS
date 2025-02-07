@extends('layouts.app')

@section('styles')
    <style>
        .rownilai:disabled{ background-color: rgb(188, 187, 187);}
        .input-group .spinner-border { color: #fff; width: 1.6rem; height: 1.6rem;}
        .btn-group-inner{ background: rgba(0,0,0, .4); position: absolute; padding: 5px 30%; width: 100%; height: 100%; z-index: 99; }
    </style>
@endsection

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">Data {{ $halaman }} {{ $wilayah->nama }} Tahun {{ @$tahun }}</h4>
            <div>
                <a href="{!! route($routes_cancel) !!}" class="btn btn-outline-light rounded-pill"><i class="fas fa-chevron-left"></i> Kembali</a>
                <div class="alert alert-info" role="alert">
                    <h5>Perhatian</h5>
                    <ol>
                        <li> Biarkan kosong jika sektor/bidang tidak memiliki nilai, jangan di isi dengan nilai 0</li>
                        <li> Masukan angka 0 jika nilai sektor/ bidang tersebut 0 (null)</li>
                        <li> Untuk desimal, ketik tanda titik (.) sebagai pemisah desimal</li>
                    </ol>
                </div>
                <div class="table-responsive mg-t-30">                    
                    <table class="table table-collapse table-striped mg-t-20">
                        <thead class="thead-dark">
                            <tr>
                                <th class="wd-40p">Sektor/Bidang</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data_bidang) > 0)
                                @foreach($data_bidang as $bidang)
                                <tr>
                                    <td>
                                        <span class="d-none d-md-inline">@for($k=0;$k<@$bidang["level"];$k++) &nbsp;&nbsp;&nbsp; @endfor</span>
                                        <label class="mg-t-5">{!! $bidang["kode_bidang"] !!} - {!! $bidang["nama_bidang"] !!}</td>
                                    </td>
                                    <td>
                                        @if($bidang["data"] == 'y')
                                        <div class="input-group">
                                            {!! Form::number('nilai[]', $bidang["nilai"], ['class' => 'form-control rownilai', 'disabled', 'id'=> 'input-' . $loop->index, 'tabindex' => $loop->index]) !!}
                                            {!! Form::hidden('id[]', $bidang["id"], ['id'=> 'id-' . $loop->index]) !!}
                                            <div class="input-group-append">
                                                <button class="btn btn-info rowedit" id="edit-{{$loop->index}}" data-id="{{$loop->index}}" type="button"><i class="fas fa-check"></i> <span class="d-none d-md-inline">Ubah</span></button>
                                                <div class="btn-group d-none" id="simpan-{{$loop->index}}" >
                                                    <div class="btn-group-inner d-none" id="inner-{{$loop->index}}">
                                                        <div class="text-center">
                                                            <div class="spinner-border"></div>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-info rowsimpan" data-id="{{$loop->index}}" data-sektor="({!! $bidang["kode_bidang"] !!}) {!! $bidang["nama_bidang"] !!}" type="button"><i class="fas fa-save"></i> <span class="d-none d-md-inline">Simpan</span></button>
                                                    <button class="btn btn-danger rowbatal" data-id="{{$loop->index}}" type="button"><i class="fas fa-ban"></i> <span class="d-none d-md-inline">Batal</span></button>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="clearfix"></div>
                    <hr>

                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::hidden('tahun', $tahun, ['id' => 'tahun']) !!}
                        <a href="{!! route($routes_cancel) !!}" class="btn btn-outline-light rounded-pill"><i class="fas fa-chevron-left"></i> Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection


@section('scripts')
    <script src="{{ asset('vendor/dashforge/lib/cleave.js/cleave.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/lib/cleave.js/addons/cleave-phone.id.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/lib/prismjs/prism.js') }}"></script>

    <script>
        $(document).ready(function() {
            $(".select2").select2();
            $('.dropify').dropify({
                messages: {
                    default: 'Drag and drop file here or click',
                    replace: 'Drag and drop file here or click to Replace',
                    remove: 'Remove',
                    error: 'Sorry, the file is too large'
                }
            });

            $(document).on("keypress",'.rownilai', function(e) {
                if (e.keyCode == 13) {
                    let thos = $(this);
                    thos.parents('tr').next().find('input.rownilai').focus();
                    return false;
                }
            })

            $(document).on('click', '.rowedit', function(e){
                let rowid = $(this).attr('data-id');
                $("#input-" + rowid).removeAttr('disabled');
                $("#edit-" + rowid).addClass('d-none');
                $("#simpan-" + rowid).removeClass('d-none');
            })

            $(document).on('click', '.rowbatal', function(e){
                let rowid = $(this).attr('data-id');
                $("#input-" + rowid).prop('disabled', 'disabled');
                $("#edit-" + rowid).removeClass('d-none');
                $("#simpan-" + rowid).addClass('d-none');
            })

            $(document).on('click', '.rowsimpan', function(e){
                let actid = $(this).attr('data-id');
                let sektor = $(this).attr('data-sektor');
                
                Swal.fire({
                    title: "Yakin Ubah data "+sektor+"?",
                    text: 'Anda akan merubah data Tahun {{ @$tahun }}!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ubah',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#0168fa',
                    cancelButtonColor: '#dc3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: "POST",
                            url: "{{ route($routes_action) }}",
                            dataType: "json",
                            data: {
                                _token:'{{ csrf_token() }}',
                                id: $("#id-" + actid).val(),
                                tahun: $("#tahun").val(),
                                nilai:$("#input-" + actid).val()
                            },
                            beforeSend: function(){
                                $("#inner-"+ actid).removeClass('d-none');
                            },
                            success: function(res){
                                if(res.valid == true){
                                    Swal.fire({
                                        text: res.message,
                                        icon: 'success',
                                        confirmButtonColor: '#0168fa',
                                        cancelButtonColor: '#dc3545',
                                    });
                                    $("#input-" + actid).prop('disabled', 'disabled');
                                    $("#edit-" + actid).removeClass('d-none');
                                    $("#simpan-" + actid).addClass('d-none');
                                }
                                else{
                                    Swal.fire({
                                        text: res.message,
                                        icon: 'error',
                                        confirmButtonColor: '#0168fa',
                                        cancelButtonColor: '#dc3545',
                                    });
                                }          
                                $("#inner-"+ actid).addClass('d-none');                     
                            },
                            error: function(err){
                                Swal.fire({
                                    text: !err.responseJSON ? err.statusText : err.responseJSON,
                                    icon: 'error',
                                    confirmButtonColor: '#0168fa',
                                    cancelButtonColor: '#dc3545',
                                });
                                $("#inner-"+ actid).addClass('d-none');
                            }
                        });
                        return false;
                    }
                })
            })
        });
    </script>
    <!-- End Relational Form table -->
@endsection