@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-th-list"></i> Data Kegiatan</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Data Kegiatan</li>
            <li class="breadcrumb-item active"><a href="#">Pengkajian</a></li>
        </ul>
    </div>
    <style>
        tr td:last-child {
            width: 1%;
            white-space: nowrap;
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Penananggung Jawab</th>
                                    <th class="text-center">Tanggal Pengajuan</th>
                                    <th class="text-center">Tanggal Disetujui</th>
                                    <th class="text-center">Lama Kegiatan</th>
                                    <th class="text-center">Tanggal Selesai</th>
                                    <th class="text-center">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($studies as $study)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">{{ $study->head->name }}</td>
                                        <td class="text-center align-middle">{{ $study->submitted_date }}</td>
                                        <td class="text-center align-middle">{{ $study->approved_date }}</td>
                                        <td class="text-center align-middle">{{ $study->study_duration }} Hari</td>
                                        <td class="text-center align-middle">{{ $study->report_approved_date ? $study->report_approved_date : "Menunggu"  }}</td>
                                        <td>
                                            <a href="/{{ explode('.', Route::currentRouteName())[0] }}/{{ $study->id }}"
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
