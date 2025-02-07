<div class="row">
    <div class="col-sm-6">
        <!-- Id Suku Dinas Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('id_wilayah', 'Wilayah:') !!}
            {!! Form::select('id_wilayah', $masterwilayah, @$anggotaDprd->id_wilayah, [
                'class' => 'form-control select2',
                'required',
                'placeholder' => 'Pilih Wilayah',
            ]) !!}
        </div>

        <!-- Nama Lengkap Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('nama_lengkap', 'Nama Lengkap:', ['class' => 'd-block']) !!}
            {!! Form::text('nama_lengkap', @$anggotaDprd->nama_lengkap, ['class' => 'form-control', 'required']) !!}
        </div>

        <!-- Id Jabatan Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('id_komisi', 'Jabatan:') !!}
            {!! Form::select('id_komisi', $masterkomisi, @$anggotaDprd->id_komisi, [
                'class' => 'form-control',
                'required',
                'placeholder' => 'Pilih Komisi',
            ]) !!}
        </div>

        <!-- Tahun Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('tahun', 'Tahun:') !!}
            {!! Form::number('tahun', $anggotaDprd->tahun ?? date('Y'), [
                'class' => 'form-control date',
                'required',
                'min' => '1945',
                'max' => date('Y'),
                'required',
            ]) !!}
        </div>

        <!-- Periode Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('periode', 'Periode:', ['class' => 'd-block']) !!}
            {!! Form::text('periode', @$anggotaDprd->periode, ['class' => 'form-control']) !!}
        </div>
    </div>
    <!-- Foto Field -->
    <div class="form-group col-xs-12 col-sm-3">
        {!! Form::label('foto', 'Foto:', ['class' => 'd-block']) !!}
        {!! Form::file('foto', [
            'class' => 'form-control dropify',
            'data-default-file' => @$anggotaDprd->foto ? asset(@$anggotaDprd->foto) : '',
            'data-max-file-size' => '2M',
            @$anggotaDprd->foto ? '' : 'required',
        ]) !!}
    </div>
</div>


{{-- <!-- Created By Field -->
<div class="form-group col-sm-6">
    {!! Form::label('created_by', 'Created By:') !!}
    {!! Form::number('created_by', null, ['class' => 'form-control']) !!}
</div>

<!-- Updated By Field -->
<div class="form-group col-sm-6">
    {!! Form::label('updated_by', 'Updated By:') !!}
    {!! Form::number('updated_by', null, ['class' => 'form-control']) !!}
</div>

<!-- Deleted By Field -->
<div class="form-group col-sm-6">
    {!! Form::label('deleted_by', 'Deleted By:') !!}
    {!! Form::number('deleted_by', null, ['class' => 'form-control']) !!}
</div>

<!-- History Updated Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('history_updated', 'History Updated:', ['class' => 'd-block']) !!}
    {!! Form::textarea('history_updated', null, ['class' => 'form-control my-editor']) !!}
</div> --}}


<div class="clearfix"></div>
<hr>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('anggotaDprds.index') !!}" class="btn btn-light">Cancel</a>
</div>

@section('scripts')
    <!-- Relational Form table -->
    <script>
        $(document).ready(function() {

            $('.dropify').dropify({
                messages: {
                    default: 'Drag and drop file here or click',
                    replace: 'Drag and drop file here or click to Replace',
                    remove: 'Remove',
                    error: 'Sorry, the file is too large'
                }
            });
            var editor_config = {
                path_absolute: "/",
                selector: 'textarea.my-editor2',
                height: "250",
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern"
                ],
                menubar: false,
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                relative_urls: false,
                file_browser_callback: function(field_name, url, type, win) {
                    var x = window.innerWidth || document.documentElement.clientWidth || document
                        .getElementsByTagName('body')[0].clientWidth;
                    var y = window.innerHeight || document.documentElement.clientHeight || document
                        .getElementsByTagName('body')[0].clientHeight;

                    var cmsURL = editor_config.path_absolute + 'filemanager?field_name=' + field_name;
                    cmsURL = cmsURL + "&type=Files";

                    tinyMCE.activeEditor.windowManager.open({
                        file: cmsURL,
                        title: 'Filemanager',
                        width: x * 0.8,
                        height: y * 0.8,
                        resizable: "yes",
                        close_previous: "no"
                    });
                }
            }
            tinymce.init(editor_config);
        });
        $('.btn-add-related').on('click', function() {
            var relation = $(this).data('relation');
            var index = $(this).parents('.panel').find('tbody tr').length - 1;

            if ($('.empty-data').length) {
                $('.empty-data').hide();
            }

            // TODO: edit these related input fields (input type, option and default value)
            var inputForm = '';
            var fields = $(this).data('fields').split(',');
            // $.each(fields, function(idx, field) {
            //     inputForm += `
        //         <td class="form-group">
        //             {!! Form::select('`+relation+`[`+relation+index+`][`+field+`]', [], null, [
            'class' => 'form-control select2',
            'style' => 'width:100%',
        ]) !!}
        //         </td>
        //     `;
            // })
            $.each(fields, function(idx, field) {
                inputForm += `
                <td class="form-group">
                    {!! Form::text('`+relation+`[`+relation+index+`][`+field+`]', null, [
                        'class' => 'form-control',
                        'style' => 'width:100%',
                    ]) !!}
                </td>
            `;
            })

            var relatedForm = `
            <tr id="` + relation + index + `">
                ` + inputForm + `
                <td class="form-group" style="text-align:right">
                    <button type="button" class="btn-delete btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i></button>
                </td>
            </tr>
        `;

            $(this).parents('.panel').find('tbody').append(relatedForm);

            $('#' + relation + index + ' .select2').select2();
        });

        $(document).on('click', '.btn-delete', function() {
            var actionDelete = confirm('Are you sure?');
            if (actionDelete) {
                var dom;
                var id = $(this).data('id');
                var relation = $(this).data('relation');

                if (id) {
                    dom = `<input class="` + relation + `-delete" type="hidden" name="` + relation +
                        `-delete[]" value="` + id + `">`;
                    $(this).parents('.box-body').append(dom);
                }

                $(this).parents('tr').remove();

                if (!$('tbody tr').length) {
                    $('.empty-data').show();
                }
            }
        });
    </script>
    <!-- End Relational Form table -->
@endsection
