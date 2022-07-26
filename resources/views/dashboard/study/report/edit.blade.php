@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> Form Penyerahan Laporan Akhir</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Laporan Akhir</li>
            <li class="breadcrumb-item"><a href="#">Laporan Akhir Penelitian</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="tile mb-0">
                <form action="/{{ explode('.', Route::currentRouteName())[0] }}/{{ $report->study_id }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <h3 class="tile-title">Data Laporan Akhir Penelitian</h3>
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
                                    <label class="control-label">File Laporan Akhir</label>
                                    <div class="custom-file">
                                        <input type="file" name="report" class="custom-file-input"
                                            onchange="preview(this)" accept=".pdf" required>
                                        <label class="custom-file-label">Pilih File Laporan Akhir</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tile-footer d-flex justify-content-end">
                        <a class="btn btn-secondary" href="{{ url()->previous() }}">
                            <i class="fa fa-fw fa-lg fa-times-circle"></i>
                            Kembali
                        </a>
                        &nbsp;&nbsp;&nbsp;
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-fw fa-lg fa-check-circle"></i>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <iframe id="preview-report" src="{{ $report->file ? asset('storage/' . $report->file) : '' }}" width="100%"
                height="100%" frameborder="0"></iframe>
        </div>
    </div>
    <script>
        function preview(input) {
            const iframe = document.querySelector(`#preview-${input.getAttribute('name')}`);
            iframe.src = URL.createObjectURL(input.files[0]);
            input.nextElementSibling.innerHTML = input.files[0].name;
        }
    </script>
@endsection
