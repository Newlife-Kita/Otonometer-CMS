<ul class="nested active">
    @foreach($data as $res)
        <li>
            <div class="custom-control custom-radio">
                <input type="radio" name="pop_parent" class="custom-control-input select-parent" id="customRadio{{ $res->id }}" value="{{ $res->id }}" data-kode="{{ $res->kode }}" data-name="{{ $res->nama }}"  {{ @$current == $res->id ? 'checked' : ''}}>
                <label class="custom-control-label" for="customRadio{{ $res->id }}">{{ $res->kode }}. {{ $res->nama }}</label>
            </div>
            @if(count($res->childs))
                @include('bidangs.edit_modal',['parents' => $res->child,'current' => @$current])
            @endif
        </li>
    @endforeach
</ul>