@extends('dashboard.layouts.main')

@section('app-content')
    <div class="row user">
        <div class="col-md-3">
            <div class="tile p-0">
                <img style="object-fit: cover; width: 100%; height: 300px; object-position: 0 -12px;"
                    src="{{ asset('storage/' . $employee->file_image) }}">
            </div>
            <div class="tile p-0">
                <ul class="nav flex-column nav-tabs user-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#user-timeline" data-toggle="tab">
                            Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#user-position" data-toggle="tab">
                            Jabatan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#user-change-password" data-toggle="tab">
                            Ganti Password
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content mt-0">
                <div class="tab-pane active" id="user-timeline">
                    <div class="tile user-settings">
                        <h4 class="line-head">Profil Saya</h4>
                        <form>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label>NIP</label>
                                    <input class="form-control" type="text" value="{{ $employee->nip }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label>Nama</label>
                                    <input class="form-control" type="text" value="{{ $employee->name }}" disabled>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label>Tanggal Lahir</label>
                                    <input class="form-control" type="text" value="{{ $employee->birth }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label>Nomor Telepon</label>
                                    <input class="form-control" type="text" value="{{ $employee->phone_number }}"
                                        disabled>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label>Pendidikan Terakhir</label>
                                    <input class="form-control" type="text"
                                        value="{{ $employee->academic_background }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label>Jenis Kelamin</label>
                                    <input class="form-control" type="text"
                                        value="{{ $employee->sex == '1' ? 'Laki - Laki' : 'Perempuan' }}" disabled>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label>Alamat</label>
                                    <textarea class="form-control" cols="30" rows="3" disabled>{{ $employee->address }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-10">
                                <div class="col-md-12">
                                    <a href="/employees" class="btn btn-primary">
                                        <i class="fa fa-fw fa-lg fa-check-circle"></i> Kembali
                                    </a>
                                    <a href="/employees/{{ $employee->id }}/edit" class="btn btn-warning">
                                        <i class="fa fa-fw fa-lg fa-check-circle"></i> Edit
                                    </a>
                                    <button class="btn btn-danger" type="button">
                                        <i class="fa fa-fw fa-lg fa-check-circle"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="user-position">
                    <div class="tile user-settings">
                        <h4 class="line-head">Jabatan</h4>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label>Jabatan</label>
                                <input class="form-control" type="text" value="{{ $employee->position }}" disabled>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label>Terhitung Mulai Tanggal</label>
                                <input class="form-control" type="text" value="{{ $employee->start_date }}" disabled>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label>Status Pegawai</label>
                                <input class="form-control" type="text"
                                    value="{{ $employee->status === 'INTERNAL' ? 'Internal' : 'Eksternal' }}" disabled>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="d-block">Dokumen</label>
                                <ul>
                                    <li><a href="{{ asset('storage/' . $employee->file_sk_pengangkatan) }}">Surat
                                            Pernyataan Pengangkatan</a></li>
                                    <li><a href="{{ asset('storage/' . $employee->file_ijazah) }}">Izajah</a></li>
                                    <li><a href="{{ asset('storage/' . $employee->file_ktp) }}">KTP</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
