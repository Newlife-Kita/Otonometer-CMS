<ul class="nested active">
    @foreach($data as $res)
        <li>
            @if(count($res->childs))
                <span class="caret caret-down">{{ $res->kode }}. {{ $res->nama }}</span>
                @include('bidangs.show_fields',['parents' => $res->child])
            @else
                <span>{{ $res->kode }}. {{ $res->nama }}</span>  
            @endif
        </li>
    @endforeach
</ul>