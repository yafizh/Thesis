@extends('dashboard.layouts.print')

@section('content')
    <h4 class="text-center my-3">Laporan "Laporan Akhir Penelitian"</h4>
    <section class="mb-3">
        <strong>
            <span style="width: 150px; display: inline-block;">Filter Tanggal</span>
            <span>: Tanggal Pengajuan</span>
        </strong>
        <br>
        <span style="width: 150px; display: inline-block;">Dari Tanggal</span>
        <span>: {{ empty($from) ? 'Semua' : $from }}</span>
        <br>
        <span style="width: 150px; display: inline-block;">Sampai Tanggal</span>
        <span>: {{ empty($to) ? 'Semua' : $to }}</span>

        <br>
        <br>

        <strong>
            <span style="width: 150px; display: inline-block;">Filter Status</span>
            <span>: Status Pengajuan</span>
        </strong>
        <br>
        <span style="width: 150px; display: inline-block;">Status</span>
        <span>: {{ $status }}</span>
    </section>
    <main>
        <table class="table table-striped table-bordered">
            <thead class="text-center">
                <tr>
                    <th rowspan="2" class="text-center align-middle">No</th>
                    <th rowspan="2" class="text-center align-middle">Judul</th>
                    <th colspan="2" class="text-center">Penanggung Jawab</th>
                    <th colspan="2" class="text-center">Peninjau</th>
                    <th rowspan="2" class="text-center align-middle">Tanggal Pengajuan
                    </th>
                    <th rowspan="2" class="text-center align-middle">Status</th>
                    <th rowspan="2" class="text-center align-middle">Lama Peninjauan
                    </th>
                </tr>
                <tr>
                    <th class="text-center">NIP</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">NIP</th>
                    <th class="text-center">Nama</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                    <tr>
                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                        <td class="text-center align-middle">{{ $report->title }}</td>
                        <td class="text-center align-middle">{{ $report->head->nip }}</td>
                        <td class="align-middle">{{ $report->head->name }}</td>
                        @if (isset($report->reviewer->nip) && isset($report->reviewer->name))
                            <td class="text-center align-middle">
                                {{ $report->reviewer->nip }}</td>
                            <td class="align-middle">{{ $report->reviewer->name }}</td>
                        @else
                            <td colspan="2" class="text-center align-middle">
                                {{ $report->reviewer }}</td>
                        @endif
                        <td class="text-center align-middle">{{ $report->submitted_date }}</td>
                        <td class="text-center align-middle">{{ $report->status }}</td>
                        <td class="text-center align-middle">{{ $report->approved_duration }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
@endsection
