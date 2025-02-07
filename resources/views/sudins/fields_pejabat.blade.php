<div class="row">
    <div class="col-sm-6">
        <!-- Id Suku Dinas Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('id_wilayah', 'Provinsi/Kab/Kota:') !!}
            {!! Form::text('label_wilayah', @$wilayah->nama, ['class' => 'form-control', 'disabled']) !!}
            {!! Form::hidden('id_wilayah', @$wilayah->id) !!}
        </div>
        <!-- Id Suku Dinas Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('id_suku_dinas', 'Suku Dinas:') !!}
            {!! Form::text('label_suku_dinas', @$sukudinas->nama_sudin, ['class' => 'form-control', 'disabled']) !!}
            {!! Form::hidden('id_suku_dinas', @$sukudinas->id) !!}
        </div>

        <!-- Id Jabatan Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('id_jabatan', 'Jabatan:') !!}
            {!! Form::select('id_jabatan', $masterjabatan, @$pejabatsudin->id_jabatan, [
                'class' => 'form-control select2',
                'required',
                'placeholder' => 'Pilih Jabatan',
            ]) !!}
        </div>

        <!-- Nama Lengkap Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('nama_lengkap', 'Nama Lengkap:', ['class' => 'd-block']) !!}
            {!! Form::text('nama_lengkap', @$pejabatsudin->nama_lengkap, ['class' => 'form-control', 'required']) !!}
        </div>

        <!-- NIP Lengkap Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('nip', 'NIP:', ['class' => 'd-block']) !!}
            {!! Form::text('nip', @$pejabatsudin->nip, ['class' => 'form-control']) !!}
        </div>

        <!-- Contact Lengkap Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('contact', 'Hp/Mobile:', ['class' => 'd-block']) !!}
            <div class="input-group mg-b-10">
                <div class="input-group-prepend">
                    <span class="input-group-text">+62</span>
                </div>
                {!! Form::number('contact', @$pejabatsudin->contact, ['class' => 'form-control']) !!}
            </div>
        </div>

        <!-- Email Lengkap Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('email', 'Alamat Email:', ['class' => 'd-block']) !!}
            {!! Form::email('email', @$pejabatsudin->email, ['class' => 'form-control']) !!}
        </div>

        @if (empty($pejabatsudin))
            <div class="form-group col-sm-3">
                <div class="custom-control custom-checkbox">
                    {!! Form::checkbox('usetahun', 'y', @old('usetahun') ? true : false, [
                        'class' => 'custom-control-input',
                        'id' => 'usetahun',
                    ]) !!}
                    <label class="custom-control-label" for="usetahun">Single Tahun</label>
                </div>
            </div>

            <!-- Tahun Field -->
            <div class="form-group col-sm-3 {{ @old('usetahun') ? '' : 'd-none' }}" id="tahun-box">
                {!! Form::label('tahun', 'Tahun:') !!}
                {!! Form::number('tahun', date('Y'), [
                    'class' => 'form-control date',
                    'required',
                    'min' => '1945',
                    'max' => date('Y'),
                    'required',
                ]) !!}
            </div>

            <!-- Periode Field -->
            <div class="form-group col-sm-8">
                {!! Form::label('periode', 'Periode:') !!}
                <div class="input-group mg-b-10">
                    {!! Form::number('tahun_lantik', date('Y'), [
                        'class' => 'form-control date',
                        'required',
                        'min' => '1945',
                        'max' => date('Y'),
                        'required',
                    ]) !!}
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2"> S.D </span>
                    </div>
                    {!! Form::number('tahun_akhir', date('Y'), [
                        'class' => 'form-control date',
                        'required',
                        'min' => '1945',
                        'required',
                    ]) !!}
                </div>
            </div>
        @else
            <!-- Tahun Field -->
            <div class="form-group col-sm-3">
                {!! Form::label('tahun', 'Tahun:') !!}
                {!! Form::number('tahun', $pejabatsudin->tahun ?? date('Y'), [
                    'class' => 'form-control date',
                    'required',
                    'min' => '1945',
                    'max' => date('Y'),
                    'required',
                ]) !!}
            </div>

            <!-- Periode Field -->
            <div class="form-group col-sm-8">
                {!! Form::label('periode', 'Periode:') !!}
                <div class="input-group mg-b-10">
                    {!! Form::number('tahun_lantik', $pejabatsudin->tahun_lantik ?? date('Y'), [
                        'class' => 'form-control date',
                        'required',
                        'min' => '1945',
                        'max' => date('Y'),
                        'required',
                    ]) !!}
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2"> S.D </span>
                    </div>
                    {!! Form::number('tahun_akhir', $pejabatsudin->tahun_akhir ?? date('Y'), [
                        'class' => 'form-control date',
                        'required',
                        'min' => '1945',
                        'required',
                    ]) !!}
                </div>
            </div>
            {!! Form::hidden('id', @$pejabatsudin->id) !!}
        @endif
    </div>
    <!-- Foto Field -->
    <div class="form-group col-xs-12 col-sm-3">
        {!! Form::label('foto', 'Foto:', ['class' => 'd-block']) !!}
        {!! Form::file('foto', [
            'class' => 'form-control dropify',
            'data-default-file' => @$pejabatsudin->foto ? env('APP_STORAGE') . @$pejabatsudin->foto : '',
            'data-max-file-size' => '2M',
            'data-min-height' => '400',
            'data-max-height' => '400',
            'data-min-width' => '300',
            'data-max-width' => '300',
            'data-allowed-file-extensions' => 'png jpg jpeg webp svg heif heic',
        ]) !!}
    </div>
</div>

<div class="clearfix"></div>
<hr>


@section('scripts')
    <!-- Relational Form table -->
    <script>
        $(document).ready(function() {
            var cropper;
            $(".select2").select2();

            // Initialize Dropify
            $('.dropify').dropify({
                messages: {
                    default: 'Drag and drop file here or click',
                    replace: 'Drag and drop file here or click to Replace',
                    remove: 'Remove',
                    error: 'Sorry, the file is too large'
                }
            });

            // Handle Dropify error event
            $('.dropify').on(
                'dropify.error.minWidth dropify.error.maxWidth dropify.error.minHeight dropify.error.maxHeight',
                function(event, element) {
                    // Store file information
                    const fileName = element.input[0].files[0].name;
                    const fileType = element.input[0].files[0].type;
                    const input = element.input[0];

                    // Use FileReader to read the file
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const dataUrl = e.target.result;

                        // Create a custom SweetAlert modal with Cropper
                        Swal.fire({
                            title: 'Crop Image',
                            html: '<div><img id="cropper" src="' + dataUrl +
                                '"  style="display: block; width: 100%; max-width: 300px;"/></div>',
                            showCancelButton: true,
                            showConfirmButton: true,
                            confirmButtonText: 'Crop',
                            didOpen: () => {
                                // Set Cropper options directly on the image element
                                $('#cropper').cropper({
                                    aspectRatio: 150 / 200,
                                    crop: function(event) {
                                        console.log(event.detail.x);
                                        console.log(event.detail.y);
                                        console.log(event.detail.width);
                                        console.log(event.detail.height);
                                        console.log(event.detail.rotate);
                                        console.log(event.detail.scaleX);
                                        console.log(event.detail.scaleY);
                                    }
                                });

                                cropper = $('#cropper').data('cropper');
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Get the cropped canvas
                                console.log(cropper);
                                const croppedCanvas = cropper.getCroppedCanvas();

                                // Convert the canvas to Blob
                                croppedCanvas.toBlob(function(blob) {
                                    // Convert the Blob to a File
                                    const croppedFile = new File([blob], fileName, {
                                        type: fileType
                                    });

                                    // Set the cropped file back to the input
                                    const fileInput = input;
                                    const dataTransfer = new DataTransfer();
                                    dataTransfer.items.add(croppedFile);
                                    fileInput.files = dataTransfer.files;

                                    resetPreview(fileInput, croppedCanvas.toDataURL(),
                                        fileName);
                                });
                            } else {
                                input.value = '';
                            }

                            // Destroy the Cropper instance
                            $('#cropper').cropper('destroy');
                        });
                    };

                    // Read the file as a data URL
                    reader.readAsDataURL(element.input[0].files[0]);
                });
        });

        function resetPreview(element, src, fname = '') {
            let input = $(element);
            let wrapper = input.closest('.dropify-wrapper');
            let preview = wrapper.find('.dropify-preview');
            let filename = wrapper.find('.dropify-filename-inner');
            let render = wrapper.find('.dropify-render').html('');

            wrapper.removeClass('has-error').addClass('has-preview');

            render.append($('<img />').attr('src', src).css('max-height', input.data('height') || ''));
            preview.fadeIn();
        }

        @if (empty($pejabatsudin))
            $(document).on('click', '#usetahun', function() {
                if ($(this).prop('checked') == true) $("#tahun-box").removeClass('d-none');
                else $("#tahun-box").addClass('d-none');
            })
        @endif
    </script>
    <!-- End Relational Form table -->
@endsection
