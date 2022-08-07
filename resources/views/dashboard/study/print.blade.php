@extends('dashboard.layouts.print')

@section('content')
    <h4 class="text-center my-3">Laporan Data Pengkajian</h4>
    <section class="mb-3">
        <strong>
            <span style="width: 150px; display: inline-block;">Filter Tanggal</span>
            <span>: Tanggal Mulai Pengkajian</span>
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
            <span>: Status Pengkajian</span>
        </strong>
        <br>
        <span style="width: 150px; display: inline-block;">Status</span>
        <span>: {{ $status }}</span>
    </section>
    <main>
        <table class="table table-striped table-bordered">
            <thead class="text-center">
                <tr>
                    <th rowspan="2" style="vertical-align: middle;" class="text-center">No</th>
                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Judul</th>
                    <th colspan="2" class="text-center">Penanggung Jawab</th>
                    <th colspan="2" class="text-center">Peninjau</th>
                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Tanggal Mulai Pengkajian
                    </th>
                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Status</th>
                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Lama Pengkajian</th>
                </tr>
                <tr>
                    <th class="text-center">NIP</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">NIP</th>
                    <th class="text-center">Nama</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($studies as $study)
                    <tr>
                        <td style="vertical-align: middle;" class="text-center">{{ $loop->iteration }}</td>
                        <td style="vertical-align: middle;" class="text-center">{{ $study->title }}</td>
                        <td style="vertical-align: middle;" class="text-center">{{ $study->head->nip }}</td>
                        <td style="vertical-align: middle;">{{ $study->head->name }}</td>
                        @if (isset($study->reviewer->nip) && isset($study->reviewer->name))
                            <td style="vertical-align: middle;" class="text-center">
                                {{ $study->reviewer->nip }}</td>
                            <td style="vertical-align: middle;">{{ $study->reviewer->name }}</td>
                        @else
                            <td colspan="2" style="vertical-align: middle;" class="text-center">
                                {{ $study->reviewer }}</td>
                        @endif
                        <td style="vertical-align: middle;" class="text-center">{{ $study->start_date }}</td>
                        <td style="vertical-align: middle;" class="text-center">{{ $study->status }}</td>
                        <td style="vertical-align: middle;" class="text-center">{{ $study->study_duration }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
@endsection
