@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-th-list"></i> Data Pegawai</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Data Master</li>
            <li class="breadcrumb-item active"><a href="#">Data Pegawai</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                @if (session()->has('created'))
                    <div class="alert alert-success flex-grow-1">
                        Berhasil Menambahkan Data Pegawai!
                        <a href="/{{ explode('.', Route::currentRouteName())[0] }}/{{ session()->get('created') }}"
                            class="alert-link">
                            Lihat
                        </a>
                    </div>
                @elseif (session()->has('updated'))
                    <div class="alert alert-success flex-grow-1">
                        Berhasil Memperbaharui Data Pegawai!
                        <a href="/{{ explode('.', Route::currentRouteName())[0] }}/{{ session()->get('updated') }}"
                            class="alert-link">
                            Lihat
                        </a>
                    </div>
                @elseif (session()->has('deleted'))
                    <div class="alert alert-success flex-grow-1">
                        Berhasil Menghapus Data Pegawai dengan nama: <strong>{{ session()->get('deleted') }}</strong>!
                    </div>
                @else
                    <div class="alert alert-info flex-grow-1">
                        Klik Tombol <strong>Tambah Pegawai</strong> Untuk Menambahkan Data Pegawai!
                    </div>
                @endif
                <div class="ml-3 pt-1">
                    <a href="/{{ explode('.', Route::currentRouteName())[0] }}/create" class="btn btn-primary p-2">
                        Tambah Pegawai
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
                                    <th class="text-center">Jabatan</th>
                                    <th class="text-center">Nomor Telepon</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">TMT</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td class="text-center align-middle">{{ $employee->nip }}</td>
                                        <td class="align-middle">{{ $employee->name }}</td>
                                        <td class="align-middle">{{ $employee->position }}</td>
                                        <td class="text-center align-middle">{{ $employee->phone_number }}</td>
                                        <td class="text-center align-middle">
                                            {{ $employee->status === 'INTERNAL' ? 'Internal' : 'Eksternal' }}
                                        </td>
                                        <td class="text-center align-middle">
                                            {{ date_format(date_create($employee->start_date), 'd-m-Y') }}</td>
                                        <td class="td-fit">
                                            <a href="/employees/{{ $employee->id }}" class="btn btn-info btn-sm"><i
                                                    class="m-0 fa fa-eye" aria-hidden="true"></i></a>
                                            <a href="/employees/{{ $employee->id }}/edit" class="btn btn-warning btn-sm"><i
                                                    class="m-0 fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                            <form action="/employees/{{ $employee->id }}" method="POST" class="d-inline">
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
