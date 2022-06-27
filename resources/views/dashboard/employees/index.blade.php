@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-th-list"></i> Data Pegawai</h1>
            <p>Table to display analytical data effectively</p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Pegawai</li>
            <li class="breadcrumb-item active"><a href="#">Data Pegawai</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-header d-flex justify-content-end mb-5">
                    <a href="/employees/create" class="btn btn-primary">Tambah</a>
                </div>
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Nomor Telepon</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td style="vertical-align: middle;">{{ $employee->nip }}</td>
                                        <td style="vertical-align: middle;">{{ $employee->name }}</td>
                                        <td style="vertical-align: middle;">{{ $employee->phone_number }}</td>
                                        <td style="vertical-align: middle;">{{ $employee->status }}</td>
                                        <td>
                                            <a href="" class="btn btn-info btn-sm"><i class="m-0 fa fa-eye" aria-hidden="true"></i></a>
                                            <a href="/employees/{{ $employee->id }}/edit" class="btn btn-warning btn-sm"><i class="m-0 fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                            <form action="/employees/{{ $employee->id }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Yakin?')" type="submit" class="btn btn-danger btn-sm"><i class="m-0 fa fa-trash-o" aria-hidden="true"></i></button>
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
