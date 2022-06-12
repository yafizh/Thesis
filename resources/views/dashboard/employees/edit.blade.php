@extends('dashboard.layouts.main')

@section('content')
    <div class="row gx-3">
        <div class="col-md-12">
            <div class="d-flex flex-column" style="height: 96.5vh;">
                <div class="d-flex mb-3">
                    <div
                        class="p-3 bg-white d-flex justify-content-center align-items-center rounded shadow flex-grow-1 h-100">
                        <h5 class="m-0 text-uppercase">Ubah Data Pegawai</h5>
                    </div>
                </div>
                <form class="h-100" action="/employees/{{ $employee->id }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="d-flex h-100">
                        <div class="p-3 bg-white rounded shadow">
                            <div class="mb-3">
                                <label for="image" class="form-label d-block">Gambar</label>
                                <div class="image-placeholder d-none d-flex align-items-center justify-content-center">
                                    Tidak ada gambar
                                </div>
                                <img src="{{ asset('storage/' . $employee->photo) }}" class="rounded">
                            </div>
                            <div class="mb-3">
                                <label for="photo" class="form-label">File Gambar</label>
                                <input type="file" class="form-control" onchange="previewImage(this)" name="photo" id="photo" accept="image/*">
                            </div>
                        </div>
                        <div class="ps-3 flex-grow-1">
                            <div class="p-3 bg-white h-100 rounded shadow" style="position: relative;">
                                <div class="mb-3">
                                    <label for="nip" class="form-label">NIP</label>
                                    <input type="text" class="form-control" value="{{ old('nip', $employee->nip) }}"
                                        name="nip" id="nip" required autocomplete="off" autofocus>
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" class="form-control" value="{{ old('name', $employee->name) }}"
                                        name="name" id="name" required autocomplete="off">
                                </div>
                                <div class="mb-3">
                                    <label for="position" class="form-label">Jabatan</label>
                                    <input type="text" class="form-control"
                                        value="{{ old('position', $employee->position) }}" name="position" id="position"
                                        required autocomplete="off">
                                </div>
                                <div class="mb-3">
                                    <label for="sex" class="form-label d-block">Jenis
                                        Kelamin</label>
                                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                        <input type="radio" class="btn-check" name="sex" value="1" id="sex1"
                                            {{ old('sex', $employee->sex) === 1 ? 'checked' : '' }} autocomplete="off"
                                            checked>
                                        <label class="btn btn-outline-primary" for="sex1">Laki - Laki</label>

                                        <input type="radio" class="btn-check" name="sex" value="0" id="sex2"
                                            {{ old('sex', $employee->sex) === 0 ? 'checked' : '' }} autocomplete="off">
                                        <label class="btn btn-outline-primary" for="sex2">Perempuan</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control"
                                        value="{{ old('phone_number', $employee->phone_number) }}" name="phone_number"
                                        id="phone_number" required autocomplete="off">
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat</label>
                                    <textarea class="form-control" name="address" id="address" required autocomplete="off">{{ old('address', $employee->address) }}</textarea>
                                </div>
                                <div class="mb-3" style="position: absolute; bottom: 0; right: 1rem;">
                                    <button type="submit" class="btn btn-dark">Tambah</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
