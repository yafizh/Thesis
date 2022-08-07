@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-th-list"></i> Data Pengguna</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Data Master</li>
            <li class="breadcrumb-item active"><a href="#">Data Pengguna</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                @if (session()->has('created'))
                    <div class="alert alert-success flex-grow-1">
                        Berhasil Menambahkan <strong>{{ session()->get('created') }}</strong> Sebagai Pengguna!
                    </div>
                @elseif (session()->has('updated'))
                    <div class="alert alert-success flex-grow-1">
                        Berhasil Memperbaharui Status <strong>{{ session()->get('updated') }}</strong>!
                    </div>
                @elseif (session()->has('deleted'))
                    <div class="alert alert-success flex-grow-1">
                        Berhasil Menghapus Data Pengguna dengan nama: <strong>{{ session()->get('deleted') }}</strong>!
                    </div>
                @else
                    <div class="alert alert-info flex-grow-1">
                        Klik Tombol <strong>Tambah Pengguna</strong> Untuk Menambahkan Data Pengguna!
                    </div>
                @endif
                <div class="ml-3 pt-1">
                    <a href="/{{ explode('.', Route::currentRouteName())[0] }}/create" class="btn btn-primary p-2">
                        Tambah Pengguna
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th class="text-center">NIP</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="text-center align-middle">{{ $user->employee->nip }}</td>
                                        <td class="align-middle">{{ $user->name }}</td>
                                        <td class="text-center align-middle">{{ $user->username }}</td>
                                        <td class="text-center align-middle">
                                            @if ($user->status === 'ADMIN')
                                                {{ 'Admin' }}
                                            @elseif($user->status === 'RECEPTIONIST')
                                                {{ 'Resepsionis' }}
                                            @endif
                                        </td>
                                        <td class="td-fit">
                                            <a href="/users/{{ $user->id }}/edit" class="btn btn-warning btn-sm"><i
                                                    class="m-0 fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                            <form action="/users/{{ $user->id }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Yakin?')" type="submit"
                                                    class="btn btn-danger btn-sm"><i class="m-0 fa fa-trash-o"
                                                        aria-hidden="true"></i></button>
                                            </form>
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
