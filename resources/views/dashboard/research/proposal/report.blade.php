@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-th-list"></i> Laporan Data Proposal</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Laporan</li>
            <li class="breadcrumb-item active"><a href="#">Data Proposal</a></li>
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
                                                    {{ isset($status) ? ($status === 'Menunggu Pendanaan' ? 'selected' : '') : '' }}
                                                    value="WAITING">Menunggu Pendanaan</option>
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
                                    <th rowspan="2" style="vertical-align: middle;" class="text-center">No</th>
                                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Judul Penelitian
                                    </th>
                                    <th colspan="2" class="text-center">Penanggung Jawab</th>
                                    <th colspan="2" class="text-center">Peninjau</th>
                                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Tanggal Pengajuan
                                    </th>
                                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Status</th>
                                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Lama Peninjauan
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-center align-middle">NIP</th>
                                    <th class="text-center align-middle">Nama</th>
                                    <th class="text-center align-middle">NIP</th>
                                    <th class="text-center align-middle">Nama</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proposals as $proposal)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle">{{ $proposal->title }}</td>
                                        <td class="text-center align-middle">{{ $proposal->head->nip }}
                                        </td>
                                        <td class="align-middle">{{ $proposal->head->name }}</td>
                                        @if (isset($proposal->reviewer->nip) && isset($proposal->reviewer->name))
                                            <td class="text-center align-middle">
                                                {{ $proposal->reviewer->nip }}</td>
                                            <td class="align-middle">{{ $proposal->reviewer->name }}</td>
                                        @else
                                            <td colspan="2" class="text-center align-middle">
                                                {{ $proposal->reviewer }}</td>
                                        @endif
                                        <td class="text-center align-middle">
                                            {{ $proposal->submitted_date }}</td>
                                        <td class="text-center align-middle">{{ $proposal->status }}
                                        </td>
                                        <td class="text-center align-middle">
                                            {{ $proposal->approved_duration }}</td>
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
