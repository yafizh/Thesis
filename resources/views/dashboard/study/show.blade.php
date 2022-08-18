@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> Detail Kegiatan</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Kegiatan</li>
            <li class="breadcrumb-item"><a href="#">Pengkajian</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile mb-0">
                <div class="tile-body">
                    <div class="row">
                        <div class="col-4">
                            <h6>Anggota dan Judul Penelitian yang Dikaji</h6>
                            <div class="form-group">
                                <label class="control-label">NIP Penanggung Jawab</label>
                                <input class="form-control" type="text" value="{{ $study->head->nip }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Nama Penanggung Jawab</label>
                                <input class="form-control" type="text" value="{{ $study->head->name }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Judul Penelitian yang Dikaji</label>
                                <textarea class="form-control" rows="5" disabled>{{ $study->title }}</textarea>
                                {{-- <input class="form-control" type="text" value="{{ $study->title }}" disabled> --}}
                            </div>
                            <div class="form-group">
                                <label class="control-label">Anggaran</label>
                                <ol>
                                    @foreach ($study->budgets as $budget)
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
                                    @foreach ($study->study_member as $employee)
                                        <li>{{ $employee->name }} ({{ $employee->nip }})</li>
                                    @endforeach
                                </ol>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Anggota Penyuluh</label>
                                <ol>
                                    @foreach ($study->extensionists_member as $employee)
                                        <li>{{ $employee->name }} ({{ $employee->nip }})</li>
                                    @endforeach
                                </ol>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Status Penelitian</label>
                                <br>
                                @if ($study->status === 'WAITING')
                                    <span class="badge badge-success">Proposal Telah Ditinjau</span>
                                    <span class="badge badge-info">Sedang Berjalan</span>
                                    {{-- <span class="badge badge-warning">Menunggu Anggaran</span> --}}
                                @elseif ($study->status === 'APPROVED')
                                    <span class="badge badge-success">Proposal Telah Ditinjau</span>
                                    <span class="badge badge-success">Laporan Akhir Telah Ditinjau</span>
                                    <span class="badge badge-success">Pengkajian Selesai</span>
                                @elseif ($study->status === 'SUBMITTED')
                                    <span class="badge badge-success">Proposal Telah Ditinjau</span>
                                    <span class="badge badge-warning">Menunggu Peninjauan Laporan Akhir</span>
                                @elseif ($study->status === 'ONGOING')
                                    <span class="badge badge-success">Proposal Telah Ditinjau</span>
                                    <span class="badge badge-info">Pengkajian Sedang Berjalan</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label">File Surat Izin Penelitian</label>
                                <br>
                                <a href="/{{ explode('.', Route::currentRouteName())[0] }}/permit/{{ $study->id }}"
                                    target="_blank">PDF</a>
                            </div>
                        </div>
                        <div class="col-4">
                            <h6>Pegawai yang Meninjau Proposal</h6>
                            <div class="form-group">
                                <label class="control-label">NIP</label>
                                <input type="text" class="form-control" value="{{ $study->reviewer->nip }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Nama</label>
                                <input type="text" class="form-control" value="{{ $study->reviewer->name }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Tanggal Pengajuan</label>
                                <input type="text" class="form-control" value="{{ $study->submitted_date }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Waktu Pengajuan</label>
                                <input type="text" class="form-control" value="{{ $study->submitted_time }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Tanggal Disetujui</label>
                                <input type="text" class="form-control" value="{{ $study->approved_date }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Waktu Disetujui</label>
                                <input type="text" class="form-control" value="{{ $study->approved_time }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">File Proposal</label>
                                <br>
                                <a href="{{ asset('storage/' . $study->file_proposal) }}" target="_blank">FULL TEXT</a>
                            </div>
                        </div>
                        @if ($study->report_reviewer)
                            <div class="col-4">
                                <h6>Pegawai yang Meninjau Laporan Akhir</h6>
                                <div class="form-group">
                                    <label class="control-label">NIP</label>
                                    <input type="text" class="form-control" value="{{ $study->report_reviewer->nip }}"
                                        disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Nama</label>
                                    <input type="text" class="form-control" value="{{ $study->report_reviewer->name }}"
                                        disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Tanggal Pengajuan</label>
                                    <input type="text" class="form-control"
                                        value="{{ $study->report_submitted_date }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Waktu Pengajuan</label>
                                    <input type="text" class="form-control"
                                        value="{{ $study->report_submitted_time }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Tanggal Disetujui</label>
                                    <input type="text" class="form-control"
                                        value="{{ $study->report_approved_date }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Waktu Disetujui</label>
                                    <input type="text" class="form-control"
                                        value="{{ $study->report_approved_time }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">File Laporan Akhir</label>
                                    <br>
                                    <a href="{{ asset('storage/' . $study->file_report) }}" target="_blank">FULL TEXT</a>
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
                    @if (auth()->user()->employee->nip == $study->head->nip && !$study->report_reviewer)
                        <a class="btn btn-primary" href="/report-study/{{ $study->id }}/edit">
                            <i class="fa fa-share-square"></i>
                            Menyerahkan Laporan Akhir
                        </a>
                    @endif
                    @can('external')
                        @if ($study->status === 'WAITING')
                            <a class="btn btn-warning" href="/study/budget/{{ $study->id }}"
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
