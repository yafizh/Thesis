@extends('dashboard.layouts.main')

@section('content')
    <div class="p-3 bg-white rounded shadow">
        <form action="/employees" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar</label>
                        <img src="/images/male-placeholder-image.jpeg" class="rounded" style="width: 100%;">
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">File Gambar</label>
                        <input type="file" class="form-control" name="photo" id="photo">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text" class="form-control" value="{{ old('nip') }}" name="nip" id="nip" required
                            autocomplete="off" autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" value="{{ old('name') }}" name="name" id="name" required
                            autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Jabatan</label>
                        <input type="text" class="form-control" value="{{ old('position') }}" name="position"
                            id="position" required autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label d-block">Jenis Kelamin</label>
                        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" name="sex" value="1" id="sex1"
                                {{ old('sex') === 1 ? 'checked' : '' }} autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="sex1">Laki - Laki</label>

                            <input type="radio" class="btn-check" name="sex" value="0" id="sex2"
                                {{ old('sex') === 0 ? 'checked' : '' }} autocomplete="off">
                            <label class="btn btn-outline-primary" for="sex2">Perempuan</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" value="{{ old('phone_number') }}" name="phone_number"
                            id="phone_number" required autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control" name="address" id="address" required autocomplete="off">{{ old('address') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-dark">Tambah</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
