<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
</head>

<body>
    <table width="100%" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto;">
        <tr>
            <td>Kode</td>
            <td>Nama Daerah</td>
            <td>Tipe</td>
            <td>Kode Sektor Unggulan</td>
            <td>Nilai Sektor</td>
            <td>Ketinggian</td>
            <td>Luas Wilayah</td>
        <tr>
            @foreach ($wilayah as $item)
        <tr>
            <td>{!! $item['kode'] !!}</td>
            <td>{!! $item['nama'] !!}</td>
            <td>{!! $item['tipe'] !!}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        @endforeach
        </tr>
    </table>
</body>

</html>
