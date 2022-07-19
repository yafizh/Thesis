@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> Form Edit Pengguna</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Pengguna</li>
            <li class="breadcrumb-item"><a href="#">Edit Pengguna</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="tile">
                <form action="/users/{{ $user->id }}" method="POST">
                    @csrf
                    @method('PUT')
                    <h3 class="tile-title">Data Pengguna</h3>
                    <div class="tile-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="control-label">NIP</label>
                                    <input class="form-control" type="text" name="nip" readonly required value="{{ $user->employee->nip }}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Nama</label>
                                    <input class="form-control" type="text" name="name" readonly required value="{{ $user->name }}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Username</label>
                                    <input class="form-control" type="text" name="username" readonly required value="{{ $user->username }}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Password</label>
                                    <input class="form-control" type="password" name="password"
                                        placeholder="Masukkan Password" autocomplete="off" required value="{{ $user->password }}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Status</label>
                                    <select class="form-control" name="status" required>
                                        <option value="" selected disabled>Pilih Status</option>
                                        <option {{ $user->status === 'ADMIN' ? 'selected' : '' }} value="ADMIN">Admin</option>
                                        <option {{ $user->status === 'RECEPTIONIST' ? 'selected' : '' }} value="RECEPTIONIST">Resepsionis</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tile-footer d-flex justify-content-end">
                        <a class="btn btn-secondary" href="/users">
                            <i class="fa fa-fw fa-lg fa-times-circle"></i>
                            Kembali
                        </a>
                        &nbsp;&nbsp;&nbsp;
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-fw fa-lg fa-check-circle"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
