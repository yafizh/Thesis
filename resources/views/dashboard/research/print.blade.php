@extends('dashboard.layouts.print')

@section('content')
    <h4 class="text-center my-3">Laporan Data Penelitian</h4>
    <section class="mb-3">
        <strong>
            <span style="width: 150px; display: inline-block;">Filter Tanggal</span>
            <span>: Tanggal Mulai Penelitian</span>
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
            <span>: Status Penelitian</span>
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
                    <th rowspan="2" class="text-center align-middle">Judul Penelitian</th>
                    <th colspan="2" class="text-center align-middle">Penanggung Jawab</th>
                    <th colspan="2" class="text-center align-middle">Peninjau</th>
                    <th rowspan="2" class="text-center align-middle">Tanggal Mulai Penelitian
                    </th>
                    <th rowspan="2" class="text-center align-middle">Status</th>
                    <th rowspan="2" class="text-center align-middle">Lama Penelitian</th>
                </tr>
                <tr>
                    <th class="text-center align-middle">NIP</th>
                    <th class="text-center align-middle">Nama</th>
                    <th class="text-center align-middle">NIP</th>
                    <th class="text-center align-middle">Nama</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($researches as $research)
                    <tr>
                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $research->title }}</td>
                        <td class="text-center align-middle">{{ $research->head->nip }}</td>
                        <td class="align-middle">{{ $research->head->name }}</td>
                        @if (isset($research->reviewer->nip) && isset($research->reviewer->name))
                            <td class="text-center align-middle">
                                {{ $research->reviewer->nip }}</td>
                            <td class="align-middle">{{ $research->reviewer->name }}</td>
                        @else
                            <td colspan="2" class="text-center align-middle">
                                {{ $research->reviewer }}</td>
                        @endif
                        <td class="text-center align-middle">{{ $research->start_date }}</td>
                        <td class="text-center align-middle">{{ $research->status }}</td>
                        <td class="text-center align-middle">{{ $research->research_duration }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
@endsection
