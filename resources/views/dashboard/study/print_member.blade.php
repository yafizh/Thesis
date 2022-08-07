@extends('dashboard.layouts.print')

@section('content')
    <h4 class="text-center my-3">Laporan Anggota Penelitian</h4>
    <main>
        <table class="table table-striped table-bordered">
            <thead class="text-center">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">NIP</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Penanggung Jawab</th>
                    <th class="text-center">Peneliti</th>
                    <th class="text-center">Penyuluh</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($members as $member)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $member->nip }}</td>
                        <td>{{ $member->name }}</td>
                        <td class="text-center">{{ $member->head }}</td>
                        <td class="text-center">{{ $member->researcher }}</td>
                        <td class="text-center">{{ $member->extensionist }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
@endsection
