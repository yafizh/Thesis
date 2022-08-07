@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-th-list"></i> Laporan Data Pengkajian</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Laporan</li>
            <li class="breadcrumb-item active"><a href="#">Data Pengkajian</a></li>
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
                                        <h6>Tanggal Mulai Pengkajian</h6>
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
                                        <h6>Status Pengkajian</h6>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="control-label">Status</label>
                                            <select class="form-control" name="status">
                                                <option value="">Semua</option>
                                                <option
                                                    {{ isset($status) ? ($status === 'Selesai' ? 'selected' : '') : '' }}
                                                    value="FINISH">Selesai</option>
                                                <option
                                                    {{ isset($status) ? ($status === 'Sedang Berjalan' ? 'selected' : '') : '' }}
                                                    value="ONGOING">Sedang Berjalan</option>
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
                                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Judul</th>
                                    <th colspan="2" class="text-center">Penanggung Jawab</th>
                                    <th colspan="2" class="text-center">Peninjau</th>
                                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Tanggal Mulai
                                        Pengkajian
                                    </th>
                                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Status</th>
                                    <th rowspan="2" style="vertical-align: middle;" class="text-center">Lama Pengkajian
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
                                @foreach ($studies as $study)
                                    <tr>
                                        <td style="vertical-align: middle;" class="text-center">{{ $loop->iteration }}</td>
                                        <td style="vertical-align: middle;" class="text-center">{{ $study->title }}</td>
                                        <td style="vertical-align: middle;" class="text-center">{{ $study->head->nip }}
                                        </td>
                                        <td style="vertical-align: middle;">{{ $study->head->name }}</td>
                                        @if (isset($study->reviewer->nip) && isset($study->reviewer->name))
                                            <td style="vertical-align: middle;" class="text-center">
                                                {{ $study->reviewer->nip }}</td>
                                            <td style="vertical-align: middle;">{{ $study->reviewer->name }}</td>
                                        @else
                                            <td colspan="2" style="vertical-align: middle;" class="text-center">
                                                {{ $study->reviewer }}</td>
                                        @endif
                                        <td style="vertical-align: middle;" class="text-center">
                                            {{ $study->start_date }}</td>
                                        <td style="vertical-align: middle;" class="text-center">{{ $study->status }}
                                        </td>
                                        <td style="vertical-align: middle;" class="text-center">
                                            {{ $study->study_duration }}</td>
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
