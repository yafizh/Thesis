@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-th-list"></i> Laporan Data Pegawai</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Laporan</li>
            <li class="breadcrumb-item active"><a href="#">Data Pegawai</a></li>
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
                                        <option {{ isset($status) ? ($status === 'EXTERNAL' ? 'selected' : '') : '' }}
                                            value="EXTERNAL">Eksternal</option>
                                        <option {{ isset($status) ? ($status === 'INTERNAL' ? 'selected' : '') : '' }}
                                            value="INTERNAL">Internal</option>
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
                                    <button type="submit" name="submit" value="print"
                                        class="btn btn-primary" {{ isset($filtered) ? '' : 'disabled' }}>Cetak</button>
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
                                    <th class="text-center">No</th>
                                    <th class="text-center">NIP</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Jabatan</th>
                                    <th class="text-center">Nomor Telepon</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">TMT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                        <td class="text-center" style="vertical-align: middle;">{{ $employee->nip }}</td>
                                        <td style="vertical-align: middle;">{{ $employee->name }}</td>
                                        <td style="vertical-align: middle;">{{ $employee->position }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $employee->phone_number }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $employee->status === 'INTERNAL' ? 'Internal' : 'Eksternal' }}</td>
                                        <td class="text-center" style="vertical-align: middle;">{{ $employee->start_date }}
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
