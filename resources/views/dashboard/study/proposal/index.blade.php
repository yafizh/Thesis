@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-th-list"></i> Data Proposal Pengkajian</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Data Proposal</li>
            <li class="breadcrumb-item active"><a href="#">Data Proposal Pengkajian</a></li>
        </ul>
    </div>
    <div class="row">
        @can('employee')
            @can('internal')
                <div class="col-md-12">
                    <div class="d-flex justify-content-between">
                        @if (session()->has('created'))
                            <div class="alert alert-success flex-grow-1">
                                Berhasil Melakukan Pengajuan Proposal Pengkajian!
                                <a href="/{{ explode('.', Route::currentRouteName())[0] }}/{{ session()->get('created') }}"
                                    class="alert-link">
                                    Lihat
                                </a>
                            </div>
                        @elseif (session()->has('updated'))
                            <div class="alert alert-success flex-grow-1">
                                Berhasil Memperbaharui Data Proposal Pengkajian!
                                <a href="/{{ explode('.', Route::currentRouteName())[0] }}/{{ session()->get('updated') }}"
                                    class="alert-link">
                                    Lihat
                                </a>
                            </div>
                        @elseif (session()->has('deleted'))
                            <div class="alert alert-success flex-grow-1">
                                Berhasil Membatalkan Pengajuan Proposal dengan judul:
                                <strong>{{ session()->get('deleted') }}</strong>!
                            </div>
                        @else
                            <div class="alert alert-info flex-grow-1">
                                Klik Tombol <strong>Pengajuan Baru</strong> Untuk Melakukan Pengajuan Proposal Pengkajian!
                            </div>
                        @endif
                        <div class="ml-3 pt-1">
                            <a href="/{{ explode('.', Route::currentRouteName())[0] }}/create" class="btn btn-primary p-2">
                                Pengajuan Baru
                            </a>
                        </div>
                    </div>
                </div>
            @endcan
            @can('external')
                <div class="col-md-12">
                    <div class="d-flex justify-content-between">
                        @if (session()->has('APPROVED'))
                            <div class="alert alert-success flex-grow-1">
                                Berhasil Menyetujui Pengajuan Proposal Pengkajian dengan judul:
                                <strong>{{ session()->get('APPROVED') }}</strong>
                            </div>
                        @elseif (session()->has('REJECTED'))
                            <div class="alert alert-success flex-grow-1">
                                Berhasil Menolak Pengajuan Proposal Pengkajian dengan judul:
                                <strong>{{ session()->get('REJECTED') }}</strong>
                            </div>
                        @else
                            <div class="alert alert-info flex-grow-1">
                                Klik Tombol <strong>Detail</strong> Untuk Menyetujui/Menolak Pengajuan Proposal!
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
                                    <th class="text-center">Judul Penelitian yang Dikaji</th>
                                    <th class="text-center">Penananggung Jawab</th>
                                    <th class="text-center">Tanggal Pengajuan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center td-fit">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proposals as $proposal)
                                    <tr>
                                        <td class="text-center align-middle td-fit">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">{{ $proposal->title }}</td>
                                        <td class="text-center align-middle">{{ $proposal->head->name }}</td>
                                        <td class="text-center align-middle">{{ $proposal->submitted_date }}</td>
                                        <td class="text-center align-middle">{{ $proposal->status }}</td>
                                        <td class="td-fit">
                                            <a href="/{{ explode('.', Route::currentRouteName())[0] }}/{{ $proposal->study_id }}"
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
