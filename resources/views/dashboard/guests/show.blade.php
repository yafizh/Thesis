@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> Buku Tamu</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Buku Tamu</li>
            <li class="breadcrumb-item"><a href="#">Detail Pengunjung</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="row">
                        <div class="col-xl-8 col-12">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="control-label">NIK</label>
                                        <input class="form-control" type="text" value="{{ $guest->nik }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Nama</label>
                                        <input class="form-control" type="text" value="{{ $guest->name }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Jenis Kelamin</label>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio"
                                                    {{ $guest->sex == '1' ? 'checked' : '' }} disabled>Laki -
                                                Laki
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio"
                                                    {{ $guest->sex == '0' ? 'checked' : '' }} disabled>Perempuan
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Nomor Telepon</label>
                                        <input class="form-control" type="text" value="{{ $guest->phone_number }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Pegawai yang dikunjungi</label>
                                        <input class="form-control" type="text" value="{{ $guest->employee->name }}" disabled>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="control-label">Hari</label>
                                        <input class="form-control" type="text"
                                            value="{{ $DAY_IN_INDONESIA[$guest->created_at->format('w')] }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Tanggal</label>
                                        <input class="form-control" type="date"
                                            value="{{ $guest->created_at->toDateString() }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Jam</label>
                                        <input class="form-control" type="time"
                                            value="{{ $guest->created_at->toTimeString() }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Instansi</label>
                                        <input class="form-control" type="text" name="agency"
                                            placeholder="Asal Instansi" autocomplete="off"
                                            value="{{ old('agency', $guest->agency) }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Keperluan</label>
                                        <input class="form-control" type="text" name="necessity" placeholder="Keperluan"
                                            autocomplete="off" value="{{ old('necessity', $guest->necessity) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-12">
                            <div class="form-group text-center">
                                <label class="control-label d-block">Gambar</label>
                                <div class="image mb-3 d-flex justify-content-center">
                                    <video autoplay class="border d-none" style="width:315px; height: 315px;"></video>
                                    <img src="{{ $guest->image }}" class="border"
                                        style="width:315px; height: 315px;">
                                    <input type="text" class="d-none" value="{{ old('image', $guest->image) }}"
                                        name="image">
                                </div>
                                <div class="actions">
                                    <button type="button" class="btn btn-success btn-sm mr-2">AMBIL
                                        ULANG</button>
                                    <button type="button" class="btn btn-success btn-sm ml-2" disabled>AMBIL
                                        GAMBAR</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tile-footer d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-fw fa-lg fa-check-circle"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
