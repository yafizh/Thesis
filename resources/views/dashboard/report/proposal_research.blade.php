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
                    <form action="/{{ Route::current()->uri }}" method="POST"
                        target="{{ isset($filtered) ? '_blank' : '_self' }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Dari Tanggal</label>
                                    <input class="form-control" type="date" name="from" value="{{ $from ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Sampai Tanggal</label>
                                    <input class="form-control" type="date" name="to" value="{{ $to ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Status</label>
                                    <select class="form-control" name="status">
                                        <option value="">Semua</option>
                                        <option {{ isset($status) ? ($status === 'SUBMITTED' ? 'selected' : '') : '' }}
                                            value="SUBMITTED">Pengajuan</option>
                                        <option {{ isset($status) ? ($status === 'APPROVED' ? 'selected' : '') : '' }}
                                            value="APPROVED">Disetujui</option>
                                        <option {{ isset($status) ? ($status === 'REJECTED' ? 'selected' : '') : '' }}
                                            value="REJECTED">Ditolak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Opsi</label>
                                    <br>
                                    <a href="{{ isset($filtered) ? '/' . Route::current()->uri : '#' }}"
                                        class="btn btn-secondary" {{ isset($filtered) ? '' : 'disabled' }}>Reset</a>
                                    <button type="submit" name="submit" value="filter" class="btn btn-info"
                                        {{ isset($filtered) ? 'disabled' : '' }}>Filter</button>
                                    <button type="submit" name="submit" value="print" class="btn btn-primary"
                                        {{ isset($filtered) ? '' : 'disabled' }}>Cetak</button>
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
                                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Judul</th>
                                    <th colspan="2" class="text-center">Penanggung Jawab</th>
                                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Tanggal Pengajuan
                                    </th>
                                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Status</th>
                                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Lama Peninjauan
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-center">NIP</th>
                                    <th class="text-center">Nama</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proposals as $proposal)
                                    <tr>
                                        <td style="vertical-align: middle;" class="text-center">{{ $loop->iteration }}</td>
                                        <td style="vertical-align: middle;" class="text-center">{{ $proposal->title }}</td>
                                        <td style="vertical-align: middle;" class="text-center">{{ $proposal->head->nip }}</td>
                                        <td style="vertical-align: middle;">{{ $proposal->head->name }}</td>
                                        <td style="vertical-align: middle;" class="text-center">{{ $proposal->submitted_date }}</td>
                                        <td style="vertical-align: middle;" class="text-center">{{ $proposal->status }}</td>
                                        <td style="vertical-align: middle;" class="text-center">{{ $proposal->approved_duration }} Hari</td>
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
