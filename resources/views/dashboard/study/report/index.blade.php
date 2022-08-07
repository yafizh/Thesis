@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-th-list"></i> Data Laporan Akhir Penelitian</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Data Laporan Akhir</li>
            <li class="breadcrumb-item active"><a href="#">Data Laporan Akhir Penelitian</a></li>
        </ul>
    </div>
    <div class="row">
        @can('employee')
            @can('internal')
                <div class="col-md-12">
                    <div class="d-flex justify-content-between">
                        @if (session()->has('created'))
                            <div class="alert alert-success flex-grow-1">
                                Berhasil Melakukan Penyerahan Laporan Akhir Pengkajian!
                                <a href="/{{ explode('.', Route::currentRouteName())[0] }}/{{ session()->get('created') }}"
                                    class="alert-link">
                                    Lihat
                                </a>
                            </div>
                        @elseif (session()->has('updated'))
                            <div class="alert alert-success flex-grow-1">
                                Berhasil Memperbaharui Data Laporan Akhir Pengkajian!
                                <a href="/{{ explode('.', Route::currentRouteName())[0] }}/{{ session()->get('updated') }}"
                                    class="alert-link">
                                    Lihat
                                </a>
                            </div>
                        @elseif (session()->has('deleted'))
                            <div class="alert alert-success flex-grow-1">
                                Berhasil Membatalkan Penyerahan Laporan Akhir Pengkajian dengan judul:
                                <strong>{{ session()->get('deleted') }}</strong>
                            </div>
                        @else
                            <div class="alert alert-info flex-grow-1">
                                Klik Tombol <strong>Detail</strong> Untuk Memperbaharui atau Menghapus Laporan Akhir Pengkajian!
                            </div>
                        @endif
                    </div>
                </div>
            @endcan
            @can('external')
                <div class="col-md-12">
                    <div class="d-flex justify-content-between">
                        @if (session()->has('APPROVED'))
                            <div class="alert alert-success flex-grow-1">
                                Berhasil Menyetujui Penyerahan Laporan Akhir Pengkajian dengan judul:
                                <strong>{{ session()->get('APPROVED') }}</strong>
                            </div>
                        @elseif (session()->has('REJECTED'))
                            <div class="alert alert-success flex-grow-1">
                                Berhasil Menolak Penyerahan Laporan Akhir Pengkajian dengan judul:
                                <strong>{{ session()->get('REJECTED') }}</strong>
                            </div>
                        @else
                            <div class="alert alert-info flex-grow-1">
                                Klik Tombol <strong>Detail</strong> Untuk Menyetujui/Menolak Penyerahan Laporan Akhir!
                            </div>
                        @endif
                    </div>
                </div>
            @endcan
        @endcan
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th class="text-center td-fit">No</th>
                                    <th class="text-center">Penanggung Jawab</th>
                                    <th class="text-center">Tanggal Penyerahan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center td-fit">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                    <tr>
                                        <td class="text-center align-middle td-fit">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">{{ $report->head->name }}</td>
                                        <td class="text-center align-middle">{{ $report->submitted_date }}</td>
                                        <td class="text-center align-middle">{{ $report->status }}</td>
                                        <td class="td-fit">
                                            <a href="/{{ explode('.', Route::currentRouteName())[0] }}/{{ $report->study_id }}"
                                                class="btn btn-info btn-sm"><i class="m-0 fa fa-eye"
                                                    aria-hidden="true"></i></a>
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
