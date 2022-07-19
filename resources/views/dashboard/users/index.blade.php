@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-th-list"></i> Data Pengguna</h1>
            <p>Table to display analytical data effectively</p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Data Master</li>
            <li class="breadcrumb-item active"><a href="#">Data Pengguna</a></li>
        </ul>
    </div>
    <style>
        tr td:last-child {
            width: 1%;
            white-space: nowrap;
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-header d-flex justify-content-end mb-5">
                    <a href="/users/create" class="btn btn-primary">Tambah</a>
                </div>
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
                                        <td style="vertical-align: middle;" class="text-center">{{ $user->employee->nip }}</td>
                                        <td style="vertical-align: middle;">{{ $user->name }}</td>
                                        <td style="vertical-align: middle;" class="text-center">{{ $user->username }}</td>
                                        <td style="vertical-align: middle;" class="text-center">
                                            @if ($user->status === 'ADMIN')
                                                {{ 'Admin' }}
                                            @elseif($user->status === 'RECEPTIONIST')
                                                {{ 'Resepsionis' }}
                                            @endif
                                        </td>
                                        <td>
                                            <a href="" class="btn btn-info btn-sm"><i class="m-0 fa fa-eye"
                                                    aria-hidden="true"></i></a>
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
