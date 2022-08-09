@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> Detail Tamu</h1>
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
                                        @if ($guest->sex)
                                            <input class="form-control" type="text" value="Laki - Laki" disabled>
                                        @else
                                            <input class="form-control" type="text" value="Perempuan" disabled>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Nomor Telepon</label>
                                        <input class="form-control" type="text" value="{{ $guest->phone_number }}"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Pegawai yang dikunjungi</label>
                                        <input class="form-control" type="text" value="{{ $guest->employee->name }}"
                                            disabled>
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
                                        <input class="form-control" type="text" value="{{ $guest->agency }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Keperluan</label>
                                        <input class="form-control" type="text" value="{{ $guest->necessity }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-12">
                            <div class="form-group text-center">
                                <label class="control-label d-block">Gambar</label>
                                <div class="image mb-3 d-flex justify-content-center">
                                    <img src="{{ $guest->image }}" class="border" style="width:315px; height: 315px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tile-footer d-flex justify-content-between">
                    <a class="btn btn-secondary" href="{{ url()->previous() }}">
                        <i class="fa fa-wa fa-lg fa-arrow-circle-left"></i>
                        Kembali
                    </a>
                    <div>
                        <a href="/guests/{{ $guest->id }}/edit" class="btn btn-warning"><i
                                class="fa fa-fw fa-lg fa-pencil-square-o"></i> Edit Pengunjung</a>
                        <form action="/guests/{{ $guest->id }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin?')" type="submit" class="btn btn-danger"><i
                                    class="fa fa-fw fa-lg fa-trash-o"></i> Hapus Pengunjung</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
