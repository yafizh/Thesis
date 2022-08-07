@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> Detail Proposal</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Proposal</li>
            <li class="breadcrumb-item"><a href="#">Proposal Pengkajian</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="tile mb-0">
                <div class="tile-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="control-label">NIP Penanggung Jawab</label>
                                <input class="form-control" type="text" value="{{ $proposal->head->nip }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Nama Penanggung Jawab</label>
                                <input class="form-control" type="text" value="{{ $proposal->head->name }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Judul Penelitian yang Dikaji</label>
                                <input class="form-control" type="text" value="{{ $proposal->title }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Tanggal Pengajuan</label>
                                <input type="text" class="form-control" value="{{ $proposal->submitted_date }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Waktu Pengajuan</label>
                                <input type="text" class="form-control" value="{{ $proposal->submitted_time }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Anggaran</label>
                                <ol>
                                    @foreach ($proposal->budgets as $budget)
                                        <li>{{ $budget->name }} (Rp {{ number_format($budget->cost, 0, ',', '.') }})</li>
                                    @endforeach
                                </ol>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Anggota Pengkajian</label>
                                <ol>
                                    @foreach ($proposal->study_member as $employee)
                                        <li>{{ $employee->name }} ({{ $employee->nip }})</li>
                                    @endforeach
                                </ol>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Anggota Penyuluh</label>
                                <ol>
                                    @foreach ($proposal->extensionists_member as $employee)
                                        <li>{{ $employee->name }} ({{ $employee->nip }})</li>
                                    @endforeach
                                </ol>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Status Pengajuan</label>
                                <br>
                                @if ($proposal->status === 'SUBMITTED')
                                    <span class="badge badge-success">Telah Diajukan</span>
                                    <span class="badge badge-warning">Menunggu Peninjauan</span>
                                @elseif ($proposal->status === 'REJECTED')
                                    <span class="badge badge-success">Telah Ditinjau</span>
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </div>
                            @can('internal')
                                @if ($proposal->status === 'REJECTED')
                                    <div class="form-group">
                                        <label class="control-label">Tanggal Peninjauan</label>
                                        <input type="text" class="form-control" value="{{ $proposal->approved_date }}"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Waktu Peninjauan</label>
                                        <input type="text" class="form-control" value="{{ $proposal->approved_time }}"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Keterangan</label>
                                        <textarea class="form-control" disabled>{{ $proposal->comments }}</textarea>
                                    </div>
                                @endif
                            @elsecan('external')
                                <div class="form-group">
                                    <label class="control-label">Keterangan</label>
                                    <textarea class="form-control @error('comments') is-invalid @enderror" id="comments"></textarea>
                                    @error('comments')
                                        <div class="form-control-feedback text-danger">{{ $message }}</div>
                                    @enderror
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
                    <div>
                        @can('internal')
                            <a href="/{{ explode('.', Route::currentRouteName())[0] }}/{{ $proposal->study_id }}/edit"
                                class="btn btn-warning">
                                <i class="fa fa-pencil-square-o"></i>
                                Edit
                            </a>
                            &nbsp;&nbsp;&nbsp;
                            <form action="/{{ explode('.', Route::currentRouteName())[0] }}/{{ $proposal->study_id }}"
                                method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin?')" type="submit" class="btn btn-danger">
                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                    Batal
                                </button>
                            </form>
                        @elsecan('external')
                            <form action="/{{ explode('.', Route::currentRouteName())[0] }}/approve/{{ $proposal->study_id }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <textarea class="form-control" name="comments" hidden></textarea>
                                <button class="btn btn-danger" type="submit" name="submit" value="REJECTED"
                                    onclick="return confirm('Benar ingin menolak proposal ini?')">
                                    <i class="fa fa-fw fa-lg fa-times-circle"></i>
                                    Tolak
                                </button>
                                &nbsp;&nbsp;&nbsp;
                                <button class="btn btn-primary" type="submit" name="submit" value="APPROVED"
                                    onclick="return confirm('Benar ingin menyetujui proposal ini?')">
                                    <i class="fa fa-fw fa-lg fa-check-circle"></i>
                                    Setujui
                                </button>
                            </form>
                            <script>
                                document.querySelector("#comments").addEventListener('input', function() {
                                    document.querySelector("textarea[name=comments]").value = this.value;
                                });
                            </script>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <iframe id="preview-proposal" src="{{ asset('storage/' . $proposal->file) }}" width="100%" height="100%"
                frameborder="0"></iframe>
        </div>
    </div>
@endsection
