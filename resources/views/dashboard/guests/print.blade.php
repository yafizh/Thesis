@extends('dashboard.layouts.print')

@section('content')
    <h4 class="text-center my-3">Laporan Pengunjung</h4>
    <section class="mb-3">
        <strong>
            <span style="width: 150px; display: inline-block;">Filter Tanggal</span>
            <span>: Tanggal Kunjungan</span>
        </strong>
        <br>
        <span style="width: 150px; display: inline-block;">Dari Tanggal</span>
        <span>: {{ empty($from) ? 'Semua' : $from }}</span>
        <br>
        <span style="width: 150px; display: inline-block;">Sampai Tanggal</span>
        <span>: {{ empty($to) ? 'Semua' : $to }}</span>
    </section>
    <main>
        <table class="table table-striped table-bordered">
            <thead class="text-center">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">NIK</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Tanggal Berkunjung</th>
                    <th class="text-center">Pegawai yang Dikunjungi</th>
                    <th class="text-center">Keperluan Kunjungan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($guests as $guest)
                    <tr>
                        <td style="vertical-align: middle;" class="text-center">{{ $loop->iteration }}</td>
                        <td style="vertical-align: middle;" class="text-center">{{ $guest->nik }}</td>
                        <td style="vertical-align: middle;">{{ $guest->name }}</td>
                        <td style="vertical-align: middle;" class="text-center">{{ $guest->visit_date }}</td>
                        <td style="vertical-align: middle;">{{ $guest->employee }}</td>
                        <td style="vertical-align: middle;">{{ $guest->necessity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
@endsection
