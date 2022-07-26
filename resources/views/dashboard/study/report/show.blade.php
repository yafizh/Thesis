@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> Detail Laporan Akhir</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Laporan Akhir</li>
            <li class="breadcrumb-item"><a href="#">Pengkajian</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="tile mb-0">
                @can('external')
                    <form action="/{{ explode('.', Route::currentRouteName())[0] }}/approve/{{ $report->study_id }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                    @endcan
                    <div class="tile-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="control-label">NIP Penanggung Jawab</label>
                                    <input class="form-control" type="text" value="{{ $report->head->nip }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Nama Penanggung Jawab</label>
                                    <input class="form-control" type="text" value="{{ $report->head->name }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Judul Penelitian</label>
                                    <input class="form-control" type="text" value="{{ $report->title }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Tanggal Pengajuan</label>
                                    <input type="text" class="form-control" value="{{ $report->submitted_date }}"
                                        disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Anggota Penelitian</label>
                                    <ol>
                                        @foreach ($report->study_member as $employee)
                                            <li>{{ $employee->name }} ({{ $employee->nip }})</li>
                                        @endforeach
                                    </ol>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Anggota Penyuluh</label>
                                    <ol>
                                        @foreach ($report->extensionists_member as $employee)
                                            <li>{{ $employee->name }} ({{ $employee->nip }})</li>
                                        @endforeach
                                    </ol>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Status Pengajuan</label>
                                    <br>
                                    @if ($report->status === 'SUBMITTED')
                                        <span class="badge badge-success">Telah Diajukan</span>
                                        <span class="badge badge-warning">Menunggu Peninjauan</span>
                                    @elseif ($report->status === 'REJECTED')
                                        <span class="badge badge-success">Telah Ditinjau</span>
                                        <span class="badge badge-danger">Ditolak</span>
                                    @endif
                                </div>
                                @can('internal')
                                    @if ($report->status === 'REJECTED')
                                        <div class="form-group">
                                            <label class="control-label">Keterangan</label>
                                            <textarea class="form-control" disabled>{{ $report->comments }}</textarea>
                                        </div>
                                    @endif
                                @elsecan('external')
                                    <div class="form-group">
                                        <label class="control-label">Keterangan</label>
                                        <textarea class="form-control" name="comments" id="comments"></textarea>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="tile-footer d-flex justify-content-between">
                        <a class="btn btn-secondary" href="{{ url()->previous() }}">
                            <i class="fa fa-wa fa-lg fa-arrow-circle-left"></i>
                            Kembali
                        </a>
                        @can('internal')
                            <div>
                                <a href="/{{ explode('.', Route::currentRouteName())[0] }}/{{ $report->study_id }}/edit"
                                    class="btn btn-warning">
                                    <i class="fa fa-pencil-square-o"></i>
                                    Edit
                                </a>
                                &nbsp;&nbsp;&nbsp;
                                <form action="/{{ explode('.', Route::currentRouteName())[0] }}/{{ $report->study_id }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin?')" type="submit" class="btn btn-danger"><i
                                            class="fa fa-trash-o"></i>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        @elsecan('external')
                            <div>
                                <button class="btn btn-danger" type="submit" name="submit" value="REJECTED"
                                    onclick="return confirm('Benar ingin menolak laporan akhir ini?')">
                                    <i class="fa fa-fw fa-lg fa-times-circle"></i>
                                    Tolak
                                </button>
                                &nbsp;&nbsp;&nbsp;
                                <button class="btn btn-primary" type="submit" name="submit" value="APPROVED"
                                    onclick="return confirm('Benar ingin menyetujui laporan akhir ini?')">
                                    <i class="fa fa-fw fa-lg fa-check-circle"></i>
                                    Setujui
                                </button>
                            </div>
                        @endcan
                    </div>
                    @can('external')
                    </form>
                @endcan
            </div>
        </div>
        <div class="col-md-6">
            <iframe id="preview-report" src="{{ asset('storage/' . $report->file) }}" width="100%" height="100%"
                frameborder="0"></iframe>
        </div>
    </div>
@endsection
