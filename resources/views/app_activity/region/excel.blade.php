<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Modul Halaman</th>
            <th>Total View</th>
            <th>Total Download</th>
            <th>Total Simpan</th>
            <th>Total Share</th>
        </tr>

    </thead>
    <tbody>
        @foreach ($log as $item)
            <tr>
                <td rowspan="3">{{ $item->nama_wilayah }}</td>
                <td>Jelajah</td>
                <td>{{ $item->total_view_jelajah_sum }}</td>
                <td>{{ $item->total_download_jelajah_sum }}</td>
                <td>{{ $item->total_save_jelajah_sum }}</td>
                <td>{{ $item->total_share_jelajah_sum }}</td>
            </tr>
            <tr>
                <td>Utak-Atik</td>
                <td>{{ $item->total_view_utak_atik_sum }}</td>
                <td>{{ $item->total_download_utak_atik_sum }}</td>
                <td>{{ $item->total_save_utak_atik_sum }}</td>
                <td>{{ $item->total_share_utak_atik_sum }}</td>
            </tr>
            <tr>
                <td>Berkaca</td>
                <td>{{ $item->total_view_berkaca_sum }}</td>
                <td>{{ $item->total_download_berkaca_sum }}</td>
                <td>{{ $item->total_save_berkaca_sum }}</td>
                <td>{{ $item->total_share_berkaca_sum }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
