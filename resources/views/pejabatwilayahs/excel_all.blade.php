<table>
    <thead>
        <tr>
            <th>Id Wilayah</th>
            <th>Kode Wilayah</th>
            <th>Nama Wilayah</th>
            <th>Tahun</th>
            <th>Nama ketua</th>
            <th>Awal Masa Jabatan Ketua</th>
            <th>Akhir Masa Jabatan Ketua</th>
            <th>Nama wakil ketua</th>
            <th>Awal Masa Jabatan Wakil</th>
            <th>Akhir Masa Jabatan wakil</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($wilayah as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->kode }}</td>
                <td>{{ $item->nama }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
