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
                        <a class="nav-link {{ $errors->any() || session()->has('success') || session()->has('failed') ? '' : 'active' }}"
                            href="#user-timeline" data-toggle="tab">
                            Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#user-position" data-toggle="tab">
                            Jabatan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $errors->any() || session()->has('success') || session()->has('failed') ? 'active' : '' }}"
                            href="#user-change-password" data-toggle="tab">
                            Ganti Password
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content mt-0">
                <div class="tab-pane {{ $errors->any() || session()->has('success') || session()->has('failed') ? 'fade' : 'active' }}"
                    id="user-timeline">
                    <div class="tile user-settings">
                        <h4 class="line-head">Profil Saya</h4>
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
                                <input class="form-control" type="text" value="{{ $employee->phone_number }}" disabled>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label>Pendidikan Terakhir</label>
                                <input class="form-control" type="text" value="{{ $employee->academic_background }}"
                                    disabled>
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
                    </div>
                </div>
                <div class="tab-pane fade" id="user-position">
                    <div class="tile user-settings">
                        <h4 class="line-head">Jabatan</h4>
                        <div class="row mb-4">
                            <div class="col-md-12 col-xl-6">
                                <label>Jabatan</label>
                                <input class="form-control" type="text" value="{{ $employee->position }}" disabled>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12 col-xl-6">
                                <label>Terhitung Mulai Tanggal</label>
                                <input class="form-control" type="text" value="{{ $employee->start_date }}" disabled>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12 col-xl-6">
                                <label>Status Pegawai</label>
                                <input class="form-control" type="text"
                                    value="{{ $employee->status === 'INTERNAL' ? 'Internal' : 'Eksternal' }}" disabled>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="d-block">Dokumen</label>
                                <ul>
                                    <li>
                                        <a href="{{ asset('storage/' . $employee->file_sk_pengangkatan) }}"
                                            target="_blank">
                                            Surat Pernyataan Pengangkatan
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ asset('storage/' . $employee->file_ijazah) }}" target="_blank">
                                            Izajah
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ asset('storage/' . $employee->file_ktp) }}" target="_blank">KTP</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane {{ $errors->any() || session()->has('success') || session()->has('failed') ? 'active' : 'fade' }}"
                    id="user-change-password">
                    <div class="tile user-settings">
                        <h4 class="line-head">Ganti Password</h4>
                        <form
                            action="/{{ explode('.', Route::currentRouteName())[0] }}/{{ $employee->id }}/manage-password"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-mb-12 col-xl-6">
                                    @if (session()->has('success'))
                                        <div class="alert alert-success flex-grow-1">
                                            {{ session()->get('success') }}
                                            <button class="close" type="button" data-dismiss="alert">×</button>
                                        </div>
                                    @elseif (session()->has('failed'))
                                        <div class="alert alert-danger flex-grow-1">
                                            {{ session()->get('failed') }}
                                            <button class="close" type="button" data-dismiss="alert">×</button>
                                        </div>
                                    @else
                                        <div class="alert alert-info flex-grow-1">
                                            Klik Tombol <strong>Ganti Password</strong> Untuk Mengganti Password!
                                            <button class="close" type="button" data-dismiss="alert">×</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-12 col-xl-6">
                                    <label>Password Lama</label>
                                    <input class="form-control @error('old_password') is-invalid @enderror"
                                        type="password" name="old_password" autocomplete="off">
                                    @error('old_password')
                                        <div class="form-control-feedback text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-12 col-xl-6">
                                    <label>Password Baru</label>
                                    <input class="form-control @error('new_password') is-invalid @enderror"
                                        type="password" name="new_password" autocomplete="off">
                                    @error('new_password')
                                        <div class="form-control-feedback text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-12 col-xl-6">
                                    <label>Konfirmasi Password Baru</label>
                                    <input class="form-control @error('confirm_new_password') is-invalid @enderror"
                                        type="password" name="confirm_new_password" autocomplete="off">
                                    @error('confirm_new_password')
                                        <div class="form-control-feedback text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row
                                        mb-4">
                                <div class="col-12">
                                    @can('admin')
                                        <button type="submit" name="manage" value="RESET" class="btn btn-warning"
                                            onclick="return confirm('Yakin ingin melakukan Reset Password pada Pegawai ini?')">Reset
                                            Password</button>
                                    @endcan
                                    <button type="submit" name="manage" value="CHANGE" class="btn btn-primary">Ganti
                                        Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 pl-1">
                    <a class="btn btn-secondary" href="{{ url()->previous() }}">
                        <i class="fa fa-wa fa-lg fa-arrow-circle-left"></i>
                        Kembali
                    </a>
                    <a href="/{{ explode('.', Route::currentRouteName())[0] }}/{{ $employee->id }}/edit"
                        class="btn btn-warning">
                        <i class="fa fa-fw fa-lg fa-pencil-square-o"></i> Edit Profil
                    </a>
                    <button class="btn btn-danger" type="button">
                        <i class="fa fa-fw fa-lg fa-trash-o"></i> Hapus Akun
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
