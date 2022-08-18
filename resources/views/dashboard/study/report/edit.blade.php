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
                                    <label class="control-label">Judul Penelitian yang Dikaji</label>
                                    <textarea class="form-control" disabled>{{ $report->title }}</textarea>
                                    {{-- <input class="form-control" type="text" value="{{ $report->title }}" disabled> --}}
                                </div>
                                <h6>Anggaran</h6>
                                <div class="form-group">
                                    @foreach ($report->budgets as $index => $budget)
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="control-label">Nota {{ $budget->name }} (Rp
                                                    {{ number_format($budget->cost, 0, ',', '.') }})</label>
                                                <div class="custom-file">
                                                    <input type="file" name="budged[]"
                                                        class="custom-file-input {{ $errors->has('budged.' . $index) ? 'is-invalid' : '' }}"
                                                        onchange="preview(this)" accept="image/*" required>
                                                    <label class="custom-file-label">Pilih File Nota</label>
                                                </div>
                                                <small class="form-text text-muted" id="emailHelp">File note bertipe .jpg
                                                    atau .png dan
                                                    maksimal ukuran 1MB</small>
                                                @if ($errors->has('budged.' . $index))
                                                    <div class="form-control-feedback">
                                                        {{ $errors->first('budged.' . $index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
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
                                        <input type="file" name="report"
                                            class="custom-file-input @error('report') is-invalid @enderror"
                                            onchange="preview(this)" accept=".pdf" required>
                                        <label class="custom-file-label">Pilih File Laporan Akhir</label>
                                    </div>
                                    <small class="form-text text-muted">File Laporan Akhir bertipe .pdf dan
                                        maksimal ukuran 2MB</small>
                                    @error('report')
                                        <div class="form-control-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tile-footer d-flex justify-content-between">
                        <a class="btn btn-secondary" href="{{ url()->previous() }}">
                            <i class="fa fa-wa fa-lg fa-arrow-circle-left"></i>
                            Kembali
                        </a>
                        &nbsp;&nbsp;&nbsp;
                        <button class="btn btn-primary" type="submit"
                            onclick="return confirm('Yakin ingin melakukan penyerahan Laporan Akhir ini?')">
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
            input.nextElementSibling.innerHTML = input.files[0].name;
            const iframe = document.querySelector(`#preview-${input.getAttribute('name')}`);
            iframe.src = URL.createObjectURL(input.files[0]);
        }
    </script>
@endsection
