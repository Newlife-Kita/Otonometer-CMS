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
                <td rowspan="3">{{ $item->tanggal_log }}</td>
                <td>Jelajah</td>
                <td>{{ $item->total_view_jelajah }}</td>
                <td>{{ $item->total_download_jelajah }}</td>
                <td>{{ $item->total_save_jelajah }}</td>
                <td>{{ $item->total_share_jelajah }}</td>
            </tr>
            <tr>
                <td>Utak-Atik</td>
                <td>{{ $item->total_view_utak_atik }}</td>
                <td>{{ $item->total_download_utak_atik }}</td>
                <td>{{ $item->total_save_utak_atik }}</td>
                <td>{{ $item->total_share_utak_atik }}</td>
            </tr>
            <tr>
                <td>Berkaca</td>
                <td>{{ $item->total_view_berkaca }}</td>
                <td>{{ $item->total_download_berkaca }}</td>
                <td>{{ $item->total_save_berkaca }}</td>
                <td>{{ $item->total_share_berkaca }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
