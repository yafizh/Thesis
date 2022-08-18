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
                    <th rowspan="2" class="text-center align-middle">No</th>
                    <th rowspan="2" class="text-center align-middle">Judul Penelitian yang Dikaji</th>
                    <th colspan="2" class="text-center align-middle">Penanggung Jawab</th>
                    <th colspan="2" class="text-center align-middle">Peninjau</th>
                    <th rowspan="2" class="text-center align-middle">Tanggal Mulai Pengkajian
                    </th>
                    <th rowspan="2" class="text-center align-middle">Status</th>
                    <th rowspan="2" class="text-center align-middle">Lama Pengkajian</th>
                </tr>
                <tr>
                    <th class="text-center align-middle">NIP</th>
                    <th class="text-center align-middle">Nama</th>
                    <th class="text-center align-middle">NIP</th>
                    <th class="text-center align-middle">Nama</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($studies as $study)
                    <tr>
                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $study->title }}</td>
                        <td class="text-center align-middle">{{ $study->head->nip }}</td>
                        <td class="align-middle">{{ $study->head->name }}</td>
                        @if (isset($study->reviewer->nip) && isset($study->reviewer->name))
                            <td class="text-center align-middle">
                                {{ $study->reviewer->nip }}</td>
                            <td class="align-middle">{{ $study->reviewer->name }}</td>
                        @else
                            <td colspan="2" class="text-center align-middle">
                                {{ $study->reviewer }}</td>
                        @endif
                        <td class="text-center align-middle">{{ $study->start_date }}</td>
                        <td class="text-center align-middle">{{ $study->status }}</td>
                        <td class="text-center align-middle">{{ $study->study_duration }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
@endsection
