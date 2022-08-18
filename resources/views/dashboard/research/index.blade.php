@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-th-list"></i> Data Kegiatan</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Data Kegiatan</li>
            <li class="breadcrumb-item active"><a href="#">Penelitian</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle td-fit">No</th>
                                    <th class="text-center align-middle">Judul Penelitian</th>
                                    <th class="text-center align-middle">Penananggung Jawab</th>
                                    <th class="text-center align-middle">Tanggal Pengajuan</th>
                                    <th class="text-center align-middle">Tanggal Disetujui</th>
                                    <th class="text-center align-middle">Lama Kegiatan</th>
                                    <th class="text-center align-middle">Tanggal Selesai</th>
                                    <th class="text-center align-middle td-fit">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($researches as $research)
                                    <tr>
                                        <td class="text-center align-middle td-fit">{{ $loop->iteration }}</td>
                                        <td class="align-middle">{{ $research->title }}</td>
                                        <td class="text-center align-middle">{{ $research->head->name }}</td>
                                        <td class="text-center align-middle">{{ $research->submitted_date }}</td>
                                        <td class="text-center align-middle">{{ $research->approved_date }}</td>
                                        <td class="text-center align-middle">{{ $research->research_duration }} Hari</td>
                                        <td class="text-center align-middle">{{ $research->report_approved_date ? $research->report_approved_date : "Menunggu"  }}</td>
                                        <td class="td-fit">
                                            <a href="/{{ explode('.', Route::currentRouteName())[0] }}/{{ $research->id }}"
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
