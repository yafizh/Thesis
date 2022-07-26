@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-th-list"></i> Data Laporan Akhir Penelitian</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Data Laporan Akhir</li>
            <li class="breadcrumb-item active"><a href="#">Data Laporan Akhir Penelitian</a></li>
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
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Penanggung Jawab</th>
                                    <th class="text-center">Tanggal Penyerahan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">{{ $report->head->name }}</td>
                                        <td class="text-center align-middle">{{ $report->submitted_date }}</td>
                                        <td class="text-center align-middle">{{ $report->status }}</td>
                                        <td>
                                            <a href="/{{ explode('.', Route::currentRouteName())[0] }}/{{ $report->research_id }}"
                                                class="btn btn-info btn-sm"><i class="m-0 fa fa-eye"
                                                    aria-hidden="true"></i></a>
                                            @canany(['internal', 'admin'])
                                                @if (auth()->user()->status == 'ADMIN' || $report->head->nip == auth()->user()->employee->nip)
                                                    <a href="/{{ explode('.', Route::currentRouteName())[0] }}/{{ $report->research_id }}/edit"
                                                        class="btn btn-warning btn-sm"><i class="m-0 fa fa-pencil-square-o"
                                                            aria-hidden="true"></i></a>
                                                    <form
                                                        action="/{{ explode('.', Route::currentRouteName())[0] }}/{{ $report->research_id }}"
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
