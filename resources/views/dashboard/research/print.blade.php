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
                    <th rowspan="2" style="vertical-align: middle;" class="text-center">No</th>
                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Judul</th>
                    <th colspan="2" class="text-center">Penanggung Jawab</th>
                    <th colspan="2" class="text-center">Peninjau</th>
                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Tanggal Mulai Penelitian
                    </th>
                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Status</th>
                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Lama Penelitian</th>
                </tr>
                <tr>
                    <th class="text-center">NIP</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">NIP</th>
                    <th class="text-center">Nama</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($researches as $research)
                    <tr>
                        <td style="vertical-align: middle;" class="text-center">{{ $loop->iteration }}</td>
                        <td style="vertical-align: middle;" class="text-center">{{ $research->title }}</td>
                        <td style="vertical-align: middle;" class="text-center">{{ $research->head->nip }}</td>
                        <td style="vertical-align: middle;">{{ $research->head->name }}</td>
                        @if (isset($research->reviewer->nip) && isset($research->reviewer->name))
                            <td style="vertical-align: middle;" class="text-center">
                                {{ $research->reviewer->nip }}</td>
                            <td style="vertical-align: middle;">{{ $research->reviewer->name }}</td>
                        @else
                            <td colspan="2" style="vertical-align: middle;" class="text-center">
                                {{ $research->reviewer }}</td>
                        @endif
                        <td style="vertical-align: middle;" class="text-center">{{ $research->start_date }}</td>
                        <td style="vertical-align: middle;" class="text-center">{{ $research->status }}</td>
                        <td style="vertical-align: middle;" class="text-center">{{ $research->research_duration }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
@endsection
