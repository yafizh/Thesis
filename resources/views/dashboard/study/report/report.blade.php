@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-th-list"></i> Laporan Data "Laporan Akhir Pengkajian"</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Laporan</li>
            <li class="breadcrumb-item active"><a href="#">Data "Laporan Akhir Pengkajian"</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-12">
                                        <h6>Tanggal Pengajuan</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Dari Tanggal</label>
                                            <input class="form-control" type="date" name="from"
                                                value="{{ $from ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Sampai Tanggal</label>
                                            <input class="form-control" type="date" name="to"
                                                value="{{ $to ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-12">
                                        <h6>Status Pengajuan</h6>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="control-label">Status</label>
                                            <select class="form-control" name="status">
                                                <option value="">Semua</option>
                                                <option
                                                    {{ isset($status) ? ($status === 'Pengajuan' ? 'selected' : '') : '' }}
                                                    value="SUBMITTED">Pengajuan</option>
                                                <option
                                                    {{ isset($status) ? ($status === 'Disetujui' ? 'selected' : '') : '' }}
                                                    value="APPROVED">Disetujui</option>
                                                <option
                                                    {{ isset($status) ? ($status === 'Ditolak' ? 'selected' : '') : '' }}
                                                    value="REJECTED">Ditolak</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-12">
                                        <h6>Tombol Opsi</h6>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Opsi</label>
                                            <br>
                                            <button type="submit" name="submit" value="reset"
                                                class="btn btn-secondary">Reset</button>
                                            <button type="submit" name="submit" value="filter"
                                                class="btn btn-info">Filter</button>
                                            <button type="submit" name="submit" value="print"
                                                class="btn btn-success">Cetak</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center align-middle">No</th>
                                    <th rowspan="2" class="text-center align-middle">Judul Penelitian yang Dikaji</th>
                                    <th colspan="2" class="text-center">Penanggung Jawab</th>
                                    <th colspan="2" class="text-center">Peninjau</th>
                                    <th rowspan="2" class="text-center align-middle">Tanggal Pengajuan
                                    </th>
                                    <th rowspan="2" class="text-center align-middle">Status</th>
                                    <th rowspan="2" class="text-center align-middle">Lama Peninjauan
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-center">NIP</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">NIP</th>
                                    <th class="text-center">Nama</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">{{ $report->title }}</td>
                                        <td class="text-center align-middle">{{ $report->head->nip }}
                                        </td>
                                        <td>{{ $report->head->name }}</td>
                                        @if (isset($report->reviewer->nip) && isset($report->reviewer->name))
                                            <td class="text-center align-middle">
                                                {{ $report->reviewer->nip }}</td>
                                            <td class="align-middle">{{ $report->reviewer->name }}</td>
                                        @else
                                            <td colspan="2" class="text-center align-middle">
                                                {{ $report->reviewer }}</td>
                                        @endif
                                        <td class="text-center align-middle">
                                            {{ $report->submitted_date }}</td>
                                        <td class="text-center align-middle">{{ $report->status }}
                                        </td>
                                        <td class="text-center align-middle">
                                            {{ $report->approved_duration }}</td>
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
