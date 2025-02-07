@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">Data Kodepos Kabupaten/Kota {{ @$wilayah->nama }}</h4>

            <p class="mg-b-30">Please, fill all required fields before click save button.</p>

            <div style="margin-right: -15px;margin-left: -15px;">
                <div data-label="Edit" class="df-example demo-forms services-forms">
                    {!! Form::open(['id' => 'form_wilayah']) !!}
                    
                    
                    <div class="clearfix"></div>
                    <hr>
                    
                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('scripts')
    <script>
        $(function () {        
            $('.select2').select2();
        });

        $(document).on('submit', '#form_wilayah', function(){
            $.ajax({
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('panel.wilayahs.update') }}",
                data: $('#form_wilayah').serialize(),
                success: function(result) {
                    Swal.fire({
                        text: 'Catatan berhasil disimpan',
                        icon: 'success',
                    })
                }
            })
            return false;
        })
    </script>
@endsection