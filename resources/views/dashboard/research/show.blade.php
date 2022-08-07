@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> Detail Kegiatan</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Kegiatan</li>
            <li class="breadcrumb-item"><a href="#">Penelitian</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile mb-0">
                <div class="tile-body">
                    <div class="row">
                        <div class="col-4">
                            <h6>Anggota dan Judul Penelitian</h6>
                            <div class="form-group">
                                <label class="control-label">NIP Penanggung Jawab</label>
                                <input class="form-control" type="text" value="{{ $research->head->nip }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Nama Penanggung Jawab</label>
                                <input class="form-control" type="text" value="{{ $research->head->name }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Judul Penelitian</label>
                                <input class="form-control" type="text" value="{{ $research->title }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Status Penelitian</label>
                                <br>
                                @if ($research->status === 'WAITING')
                                    <span class="badge badge-warning">Menunggu Anggaran</span>
                                @elseif ($research->status === 'APPROVED')
                                    <span class="badge badge-success">Selesai</span>
                                @elseif ($research->status === 'SUBMITTED')
                                    <span class="badge badge-warning">Menunggu Peninjauan Laporan Akhir</span>
                                @elseif ($research->status === 'ONGOING')
                                    <span class="badge badge-info">Sedang Berjalan</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label">Anggaran</label>
                                <ol>
                                    @foreach ($research->budgets as $budget)
                                        <li>
                                            {{ $budget->name }} (Rp {{ number_format($budget->cost, 0, ',', '.') }})
                                            @if ($budget->memorandum)
                                                | <a href="{{ asset('storage/' . $budget->memorandum) }}"
                                                    target="_blank">NOTA</a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Anggota Penelitian</label>
                                <ol>
                                    @foreach ($research->research_member as $employee)
                                        <li>{{ $employee->name }} ({{ $employee->nip }})</li>
                                    @endforeach
                                </ol>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Anggota Penyuluh</label>
                                <ol>
                                    @foreach ($research->extensionists_member as $employee)
                                        <li>{{ $employee->name }} ({{ $employee->nip }})</li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                        <div class="col-4">
                            <h6>Pegawai yang Meninjau Proposal</h6>
                            <div class="form-group">
                                <label class="control-label">NIP</label>
                                <input type="text" class="form-control" value="{{ $research->reviewer->nip }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Nama</label>
                                <input type="text" class="form-control" value="{{ $research->reviewer->name }}"
                                    disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Tanggal Pengajuan</label>
                                <input type="text" class="form-control" value="{{ $research->submitted_date }}"
                                    disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Waktu Pengajuan</label>
                                <input type="text" class="form-control" value="{{ $research->submitted_time }}"
                                    disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Tanggal Disetujui</label>
                                <input type="text" class="form-control" value="{{ $research->approved_date }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Waktu Disetujui</label>
                                <input type="text" class="form-control" value="{{ $research->approved_time }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">File Proposal</label>
                                <br>
                                <a href="{{ asset('storage/' . $research->file_proposal) }}" target="_blank">FULL TEXT</a>
                            </div>
                        </div>
                        @if ($research->report_reviewer)
                            <div class="col-4">
                                <h6>Pegawai yang Meninjau Laporan Akhir</h6>
                                <div class="form-group">
                                    <label class="control-label">NIP</label>
                                    <input type="text" class="form-control"
                                        value="{{ $research->report_reviewer->nip }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Nama</label>
                                    <input type="text" class="form-control"
                                        value="{{ $research->report_reviewer->name }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Tanggal Pengajuan</label>
                                    <input type="text" class="form-control"
                                        value="{{ $research->report_submitted_date }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Waktu Pengajuan</label>
                                    <input type="text" class="form-control"
                                        value="{{ $research->report_submitted_time }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Tanggal Disetujui</label>
                                    <input type="text" class="form-control"
                                        value="{{ $research->report_approved_date }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Waktu Disetujui</label>
                                    <input type="text" class="form-control"
                                        value="{{ $research->report_approved_time }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">File Laporan Akhir</label>
                                    <br>
                                    <a href="{{ asset('storage/' . $research->file_report) }}" target="_blank">FULL
                                        TEXT</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="tile-footer d-flex justify-content-between">
                    <a class="btn btn-secondary" href="{{ url()->previous() }}">
                        <i class="fa fa-wa fa-lg fa-arrow-circle-left"></i>
                        Kembali
                    </a>
                    @if (auth()->user()->employee->nip == $research->head->nip && !$research->report_reviewer)
                        <a class="btn btn-primary" href="/report-research/{{ $research->id }}/edit">
                            <i class="fa fa-share-square"></i>
                            Menyerahkan Laporan Akhir
                        </a>
                    @endif
                    @can('external')
                        @if ($research->status === 'WAITING')
                            <a class="btn btn-warning" href="/research/budget/{{ $research->id }}"
                                onclick="return confirm('Yakin ingin mengirimkan pemberitahuan anggaran?')">
                                <i class="fa fa-share-square"></i>
                                Pemberitahuan Anggaran
                            </a>
                        @endif
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
