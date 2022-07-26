@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-th-list"></i> Data Proposal Pengkajian</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Data Proposal</li>
            <li class="breadcrumb-item active"><a href="#">Data Proposal Pengkajian</a></li>
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
                @can('employee')
                    @can('internal')
                        <div class="tile-header d-flex justify-content-end mb-5">
                            <a href="/{{ explode('.', Route::currentRouteName())[0] }}/create" class="btn btn-primary">Pengajuan
                                Baru</a>
                        </div>
                    @endcan
                @endcan
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Penananggung Jawab</th>
                                    <th class="text-center">Tanggal Pengajuan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proposals as $proposal)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">{{ $proposal->head->name }}</td>
                                        <td class="text-center align-middle">{{ $proposal->submitted_date }}</td>
                                        <td class="text-center align-middle">{{ $proposal->status }}</td>
                                        <td>
                                            <a href="/{{ explode('.', Route::currentRouteName())[0] }}/{{ $proposal->study_id }}"
                                                class="btn btn-info btn-sm"><i class="m-0 fa fa-eye"
                                                    aria-hidden="true"></i></a>
                                            @canany(['internal', 'admin'])
                                                @if (auth()->user()->status == 'ADMIN' || $proposal->head->nip == auth()->user()->employee->nip)
                                                    <a href="/{{ explode('.', Route::currentRouteName())[0] }}/{{ $proposal->study_id }}/edit"
                                                        class="btn btn-warning btn-sm"><i class="m-0 fa fa-pencil-square-o"
                                                            aria-hidden="true"></i></a>
                                                    <form
                                                        action="/{{ explode('.', Route::currentRouteName())[0] }}/{{ $proposal->study_id }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button onclick="return confirm('Yakin?')" type="submit"
                                                            class="btn btn-danger btn-sm"><i class="m-0 fa fa-trash-o"
                                                                aria-hidden="true"></i></button>
                                                    </form>
                                                @endif
                                            @endcan
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
