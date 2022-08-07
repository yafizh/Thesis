@extends('dashboard.layouts.print')

@section('content')
    <h4 class="text-center my-3">Laporan Data Pegawai</h4>
    <section class="mb-3">
        <strong>
            <span style="width: 150px; display: inline-block;">Filter Tanggal</span>
            <span>: Terhitung Mulai Tanggal</span>
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
            <span>: Status Kepegawaian</span>
        </strong>
        <br>
        <span style="width: 150px; display: inline-block;">Status</span>
        <span>: {{ empty($status) ? 'Semua' : ($status == "INTERNAL" ? "Internal" : "Eksternal") }}</span>
    </section>
    <main>
        <table class="table table-striped table-bordered">
            <thead class="text-center">
                <th>No</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Status</th>
                <th>TMT</th>
                <th>Lama Bekerja</th>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $employee->nip }}</td>
                        <td>{{ $employee->name }}</td>
                        <td class="text-center">{{ $employee->position }}</td>
                        <td class="text-center">
                            {{ $employee->status === 'INTERNAL' ? 'Internal' : 'Eksternal' }}</td>
                        <td class="text-center">{{ $employee->start_date }}
                        </td>
                        <td class="text-center">{{ $employee->work_duration }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
@endsection
