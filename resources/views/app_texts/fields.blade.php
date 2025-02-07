<div x-data="textType()">
    <!-- Text Id Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('text_id', 'Text Id:') !!}
        {!! Form::number('text_id', null, ['class' => 'form-control', 'readonly' => true]) !!}
    </div>
    @php
        $textRaw = json_decode($appText->text_raw, true);
    @endphp
    <!-- Choose type -->

    <div class="form-group col-sm-6">
        {{ Form::label('form', 'Pilihan Format:') }}
        {{ Form::radio('form', 'line', $textRaw['type'] == 'line' || $textRaw['type'] == null ? true : false, ['id' => 'inlineRadio1', 'data-toggle' => 'true', 'x-model' => 'type']) }}
        {{ Form::label('inlineRadio1', 'One Line Sentence', []) }}

        {{ Form::radio('form', 'list', $textRaw['type'] == 'list' ? true : false, ['id' => 'inlineRadio2', 'data-toggle' => 'false', 'x-model' => 'type']) }}
        {{ Form::label('inlineRadio2', 'Multi Line Sentence', []) }}
    </div>

    <!-- Text Field -->
    <div class="form-group col-sm-6 words" x-show="type == 'line'">
        {!! Form::label('text', 'Deskripsi:', ['class' => 'd-block']) !!}
        @foreach ($language as $lg)
            @if ($loop->index > 0)
                <div class="mg-t-10"></div>
            @endif
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{ $lg->label }} ({{ $lg->code }})</span>
                </div>
                {!! Form::text('text[' . $lg->code . ']', null, ['class' => 'form-control', 'x-model' => 'valRaw.'.$lg->code]) !!}
            </div>
        @endforeach
    </div>

    <!-- list of text -->
    <div class="form-group col-sm-6 lines" x-show="type == 'list'">
        {!! Form::label('text', 'List Text:', ['class' => 'd-block']) !!}

        @foreach ($language as $lg)
            @if ($loop->index > 0)
                <div class="mg-t-10"></div>
            @endif
            <template x-for="(text, index) in valRaw.{{ $lg->code }}">
                <div class="input-group mg-b-5">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{ $lg->label }} ({{ $lg->code }})</span>
                    </div>
                    {!! Form::text('text[' . $lg->code . ']', null, ['class' => 'form-control ', 'x-model' => 'valRaw.'.$lg->code.'[index]']) !!}
                    {!! Form::button('<i class="fa fa-trash"></i>', [
                        'class' => 'btn btn-danger',
                        'x-on:click' => "removeText(index)",
                    ])!!}
                </div>
            </template>
        @endforeach

        <div class="mg-t-10 w-100 d-flex justify-content-end">
            {!! Form::button('<i class="fa fa-plus"></i>', [
                'class' => 'btn btn-success pd-x-40 btn d-block',
                'id' => 'add-text',
                '@click' => 'addText()',
            ]) !!}
        </div>

    </div>




    <div class="clearfix"></div>
    <hr>

    <!-- Submit Field -->
    <div class="form-group col-sm-12">
        {!! Form::button('Save', ['class' => 'btn btn-primary', '@click' => 'onSubmit()']) !!}
        <a href="{!! route('app-structure.page') !!}" class="btn btn-light">Cancel</a>
    </div>
</div>


@section('scripts')
    <!-- Relational Form table -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        function textType() {
            return {
                valRaw: {!! $appText->text_raw !!},
                listRaw : { 

                },
                textRaw: {
                },
                textId: {!! $appText->text_id !!},
                type: '',
                init() {
                    this.$watch('type', () => {
                        if (this.type == 'list') {
                            this.valRaw = this.listRaw
                        } else {
                            this.valRaw = this.textRaw
                        }
                    });
                    this.type = this.valRaw.type ?? 'line';
                    if (this.type == 'list') {
                        this.listRaw = this.valRaw
                        this.textRaw = Object.fromEntries(
                            Object.entries(this.valRaw).map(
                                ([key, value]) => 
                                    key !== 'type' ? [key, ''] : [key, 'line']
                            )
                        );
                    } else {
                        this.textRaw = this.valRaw
                        this.listRaw = Object.fromEntries(
                            Object.entries(this.valRaw).map(
                                ([key, value]) => 
                                    key !== 'type' ? [key, [""]] : [key, 'list']
                            )
                        );
                        console.log(this.listRaw);
                        console.log(this.textRaw);
                        console.log(this.valRaw);
                    }
                },
                onSubmit() {
                    delete this.valRaw.type;
                    let response = {
                        _token: "{!! csrf_token() !!}",
                        text_id: this.textId,
                        text: this.valRaw,
                        _method: 'PATCH'
                    }
                    fetch('{{ route('app-text.text.update', $appText->id) }}', {
                        method: 'POST',
                        body: JSON.stringify(response),
                        headers: {
                            'content-type': 'application/json',
                        }
                    }).then(response => {
                        if (response.ok) {
                            window.location = '{{ route('app-structure.page') }}';
                        } else {
                            Swal.fire({
                                text: response.message,
                                icon: 'warning',
                                confirmButtonColor: '#0168fa'
                            })
                        }
                    })
                },
                addText() {
                    Object.keys(this.valRaw).forEach(key => {
                        if (key !== 'type') {
                            this.valRaw[key].push('');
                        }
                    });
                }, 

                removeText(index) {
                    Object.keys(this.valRaw).forEach(key => {
                        if (key !== 'type') {
                            this.valRaw[key].splice(index, 1);
                        }
                    });
                },

            }
        }

        $(document).ready(function() {
            $('.dropify').dropify({
                messages: {
                    default: 'Drag and drop file here or click',
                    replace: 'Drag and drop file here or click to Replace',
                    remove: 'Remove',
                    error: 'Sorry, the file is too large'
                }
            });
            let activeRadio = $('input[type="radio"][name="form"]:checked').val();
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

            $.each(fields, function(idx, field) {
                inputForm += `
                <td class="form-group">
                    {!! Form::text('`+relation+`[`+relation+index+`][`+field+`]', null, [
                        'class' => 'form-control',
                        'style' => 'width:100%',
                    ]) !!}
                </td>`;
            });

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

        // $('input[type="radio"]').on('change', function() {
        //     if ($(this).val() == 'line') {
        //         $('.words').show();
        //         $('.lines').hide();
        //     } else {
        //         $('.words').hide();
        //         $('.lines').show();
        //     }
        // })
    </script>
    <!-- End Relational Form table -->
@endsection
