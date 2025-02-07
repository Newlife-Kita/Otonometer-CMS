<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
</head>

<body>
    <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 auto;">            
        @foreach ($data as $detail)
            <tr>
                @foreach ($detail as $item)
                    <td>{!! $item !!}</td>
                @endforeach
            </tr>
        @endforeach
    </table>
</body>

</html>
