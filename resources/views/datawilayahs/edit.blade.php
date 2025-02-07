@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">Informasi Daerah/Tahun </h4>

            <p class="mg-b-30">Please, fill all required fields before click save button.</p>

            <div style="margin-right: -15px;margin-left: -15px;">
                <div data-label="Edit" class="df-example demo-forms services-forms">
                    {!! Form::model($datawilayah, ['route' => ['datawilayahs.update', $datawilayah->id], 'method' => 'patch']) !!}
                    @include('datawilayahs.fields')
                    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                    <a href="{!! route('datawilayahs.show', $datawilayah->id_wilayah) !!}" class="btn btn-light">Cancel</a>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

<script>
    function(settings) {
        var api = this.api();
        var rows = api.rows().nodes(); // Get the array of row nodes
        var totalImages = rows.length; // Get the total number of rows

        // Counter to keep track of the number of resolved images
        var resolvedRow = 0;

        // Iterate through each row in the table
        api.rows().every(function(index, element) {
            var row = this.node(); // Current row node

            console.log(this.data());

        });

        function runAfterImagesResolved() {
            setTimeout(function() {
                if ($('.error-row').length > 0) {
                    $('.success-msg').hide();
                    $('.error-msg').show();
                    $('#saveBtn').prop('disabled', true);
                } else {
                    $('.error-msg').hide();
                    $('.success-msg').show();
                    $('#saveBtn').prop('disabled', false);
                }
            });
        }
    }
</script>
