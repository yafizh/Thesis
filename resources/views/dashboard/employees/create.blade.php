@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> Form Pegawai</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Pegawai</li>
            <li class="breadcrumb-item"><a href="#">Tambah Pegawai</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <form action="/employees" method="POST" enctype="multipart/form-data">
                    @csrf
                    <h3 class="tile-title">Data Pegawai</h3>
                    <div class="tile-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="control-label">NIP</label>
                                    <input class="form-control" type="text" name="nip" placeholder="Masukkan NIP"
                                        autofocus autocomplete="off" value="{{ old('nip') }}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Nama</label>
                                    <input class="form-control" type="text" name="name" placeholder="Masukkan Nama"
                                        autocomplete="off" value="{{ old('name') }}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Jabatan</label>
                                    <input class="form-control" type="text" name="position"
                                        placeholder="Masukkan Jabatan" autocomplete="off" value="{{ old('position') }}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Nomor Telepon</label>
                                    <input class="form-control" type="text" name="phone_number"
                                        placeholder="Masukkan Nomor Telepon" autocomplete="off" value="{{ old('phone_number') }}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Pendidikan Terakhir</label>
                                    <select class="form-control" name="academic_background">
                                        <option value="" selected disabled>Pilih Pendidikan Terakhir</option>
                                        <option {{ old('academic_background') === "SMK/SMA" ? "selected" : "" }} value="SMK/SMA">SMK/SMA</option>
                                        <option {{ old('academic_background') === "Sarjana" ? "selected" : "" }} value="Sarjana">Sarjana</option>
                                        <option {{ old('academic_background') === "Magister" ? "selected" : "" }} value="Magister">Magister</option>
                                        <option {{ old('academic_background') === "Doktor" ? "selected" : "" }} value="Doktor">Doktor</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Status Pegawai</label>
                                    <select class="form-control" name="status">
                                        <option value="" selected disabled>Pilih Status Pegawai</option>
                                        <option {{ old('status') === "INTERNAL" ? "selected" : "" }} value="INTERNAL">Internal</option>
                                        <option {{ old('status') === "EXTERNAL" ? "selected" : "" }} value="EXTERNAL">Eksternal</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Tanggal Lahir</label>
                                    <input class="form-control" type="date" name="birth" required value="{{ old('birth') }}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Terhitung Mulai Tanggal</label>
                                    <input class="form-control" type="date" name="start_date" value="{{ old('birth', Date('Y-m-d')) }}"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Jenis Kelamin</label>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" {{ old('sex') == "1" ? "checked" : "" }} name="sex" value="1">Laki - Laki
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" {{ old('sex') == "0" ? "checked" : "" }} name="sex" value="0">Perempuan
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Alamat</label>
                                    <textarea class="form-control" name="address" rows="4" autocomplete="off">{{ old('address') }}</textarea>
                                </div>
                            </div>
                            <div class="col-6">
                                <style>
                                    .image-placeholder,
                                    .sk-pengangkatan-placeholder,
                                    .ktp-placeholder,
                                    .ijazah-placeholder {
                                        width: 100%;
                                        height: 240px;
                                        background-color: gray;
                                        color: white;
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                    }

                                    .employee-image {
                                        width: 100%;
                                        object-fit: cover;
                                        height: 240px;
                                    }

                                    iframe {
                                        width: 100%;
                                        height: 240px;

                                        /* let any clicks go trough me */
                                        pointer-events: none;
                                    }
                                </style>
                                <div class="form-group">
                                    <label class="control-label">Preview</label>
                                    <div class="row px-2">
                                        <div class="col-md-6 p-1">
                                            <div class="ktp-placeholder">
                                                Tidak Ada Dokumen</div>
                                            <iframe class="d-none">
                                                <p>This browser does not support PDF!</p>
                                            </iframe>
                                        </div>
                                        <div class="col-md-6 p-1">
                                            <div class="image-placeholder">
                                                Tidak Ada Gambar</div>
                                            <img class="employee-image d-none">
                                        </div>
                                        <div class="col-md-6 p-1">
                                            <div class="ijazah-placeholder">
                                                Tidak Ada Dokumen</div>
                                            <iframe class="d-none">
                                                <p>This browser does not support PDF!</p>
                                            </iframe>
                                        </div>
                                        <div class="col-md-6 p-1">
                                            <div class="sk-pengangkatan-placeholder">
                                                Tidak Ada Dokumen</div>
                                            <iframe class="d-none">
                                                <p>This browser does not support PDF!</p>
                                            </iframe>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-3">KTP</label>
                                    <div class="custom-file">
                                        <input type="file" name="file_ktp" class="custom-file-input"
                                            onchange="preview(this, 'ktp-placeholder', 'doc')" accept=".pdf">
                                        <label class="custom-file-label">Choose file</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Gambar</label>
                                    <div class="custom-file">
                                        <input type="file" name="file_image" class="custom-file-input"
                                            onchange="preview(this, 'image-placeholder', 'image')" accept="image/*">
                                        <label class="custom-file-label">Choose file</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Ijazah</label>
                                    <div class="custom-file">
                                        <input type="file" name="file_ijazah" class="custom-file-input"
                                            onchange="preview(this, 'ijazah-placeholder', 'doc')" accept=".pdf">
                                        <label class="custom-file-label">Choose file</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-3">SK Pengangkatan</label>
                                    <div class="custom-file">
                                        <input type="file" name="file_sk_pengangkatan" class="custom-file-input"
                                            onchange="preview(this, 'sk-pengangkatan-placeholder', 'doc')" accept=".pdf">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                                <script>
                                    function preview(input, placeholder_class, preview) {
                                        if (preview === 'image') {
                                            const placeholder = document.querySelector(`.${placeholder_class}`);
                                            const preview = placeholder.nextElementSibling;
                                            placeholder.style.display = 'none';
                                            preview.classList.remove("d-none");

                                            const oFReader = new FileReader();
                                            oFReader.readAsDataURL(input.files[0]);

                                            oFReader.onload = function(oFREvent) {
                                                preview.src = oFREvent.target.result;
                                            };
                                        } else if (preview === 'doc') {
                                            const placeholder = document.querySelector(`.${placeholder_class}`);
                                            const iframe = placeholder.nextElementSibling;
                                            placeholder.style.display = 'none';
                                            iframe.classList.remove("d-none");

                                            const oFReader = new FileReader();
                                            oFReader.readAsDataURL(input.files[0]);

                                            oFReader.onload = function(oFREvent) {
                                                iframe.src = oFREvent.target.result;
                                                placeholder.parentElement.onclick = function() {
                                                    const pdfWindow = window.open("");
                                                    pdfWindow.document.write(`
                                                        <iframe
                                                            width='100%'
                                                            height='100%'
                                                            src='${oFREvent.target.result}'>
                                                        </iframe>`);
                                                }
                                            };
                                        }
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="tile-footer d-flex justify-content-end">
                        <a class="btn btn-secondary" href="/employees">
                            <i class="fa fa-fw fa-lg fa-times-circle"></i>
                            Kembali
                        </a>
                        &nbsp;&nbsp;&nbsp;
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-fw fa-lg fa-check-circle"></i>
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
