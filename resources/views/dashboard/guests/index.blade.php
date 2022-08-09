@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-th-list"></i> Data Pengunjung</h1>
            {{-- <p>Table to display analytical data effectively</p> --}}
        </div>
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Buku Tamu</li>
            <li class="breadcrumb-item active"><a href="#">Data Pengunjung</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                @if (session()->has('updated'))
                    <div class="alert alert-success flex-grow-1">
                        Berhasil Memperbaharui Data Pengunjung!
                        <a href="/{{ explode('.', Route::currentRouteName())[0] }}/{{ session()->get('updated') }}"
                            class="alert-link">
                            Lihat
                        </a>
                    </div>
                @elseif (session()->has('deleted'))
                    <div class="alert alert-success flex-grow-1">
                        Berhasil Menghapus Data Pengunjung dengan nama: <strong>{{ session()->get('deleted') }}</strong>!
                    </div>
                @else
                    <div class="alert alert-info flex-grow-1">
                        Klik Tombol <strong>Detail</strong> Untuk Memperbaharui atau Menghapus Data Pengunjung!
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th class="text-center td-fit">No</th>
                                    <th class="text-center">Tanggal Kunjungan</th>
                                    <th class="text-center">NIK</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Nomor Telepon</th>
                                    <th class="text-center">Instansi</th>
                                    <th class="text-center">Mengunjungi</th>
                                    <th class="text-center td-fit">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($guests as $guest)
                                    <tr>
                                        <td class="text-center align-middle td-fit">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">{{ $guest->visit_date }}</td>
                                        <td class="text-center align-middle">{{ $guest->nik }}</td>
                                        <td class="align-middle">{{ $guest->name }}</td>
                                        <td class="text-center align-middle">{{ $guest->phone_number }}
                                        </td>
                                        <td class="text-center align-middle">{{ $guest->agency }}</td>
                                        <td class="text-center align-middle">{{ $guest->employee->name }}</td>
                                        <td class="td-fit">
                                            <a href="/guests/{{ $guest->id }}" class="btn btn-info btn-sm"><i
                                                    class="m-0 fa fa-eye" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
