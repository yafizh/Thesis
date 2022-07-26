@extends('dashboard.print.main')

@section('content')
    <h4 class="text-center my-3">Laporan Proposal Penelitian</h4>
    <section class="mb-3">
        <strong>
            <span style="width: 150px; display: inline-block;">Filter Tanggal</span>
            <span>: Tanggal Pengajuan</span>
        </strong>
        <br>
        <span style="width: 150px; display: inline-block;">Dari Tanggal</span>
        <span>: {{ $from }}</span>
        <br>
        <span style="width: 150px; display: inline-block;">Sampai Tanggal</span>
        <span>: {{ $to }}</span>

        <br>
        <br>

        <strong>
            <span style="width: 150px; display: inline-block;">Filter Status</span>
            <span>: Status Pengajuan</span>
        </strong>
        <br>
        <span style="width: 150px; display: inline-block;">Status</span>
        <span>: {{ is_null($status) ? 'Semua Jenis' : $status }}</span>
    </section>
    <main>
        <table class="table table-striped table-bordered">
            <thead class="text-center">
                <tr>
                    <th rowspan="2" style="vertical-align: middle;" class="text-center">No</th>
                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Judul</th>
                    <th colspan="2" class="text-center">Penanggung Jawab</th>
                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Tanggal Pengajuan
                    </th>
                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Status</th>
                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Lama Peninjauan
                    </th>
                </tr>
                <tr>
                    <th class="text-center">NIP</th>
                    <th class="text-center">Nama</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($proposals as $proposal)
                    <tr>
                        <td style="vertical-align: middle;" class="text-center">{{ $loop->iteration }}</td>
                        <td style="vertical-align: middle;" class="text-center">{{ $proposal->title }}</td>
                        <td style="vertical-align: middle;" class="text-center">{{ $proposal->head->nip }}</td>
                        <td style="vertical-align: middle;">{{ $proposal->head->name }}</td>
                        <td style="vertical-align: middle;" class="text-center">{{ $proposal->submitted_date }}</td>
                        <td style="vertical-align: middle;" class="text-center">{{ $proposal->status }}</td>
                        <td style="vertical-align: middle;" class="text-center">{{ $proposal->approved_duration }} Hari</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
@endsection
