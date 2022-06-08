@extends('dashboard.layouts.main')

@section('content')
    <div class="row gx-3">
        <div class="col">
            <div class="p-3 bg-white rounded shadow">
                <form action="/employees/{{ $employee->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar</label>
                                <img src="{{ asset('storage/' . $employee->photo) }}" class="rounded"
                                    style="width: 100%; height: 25.3rem; object-fit: cover; object-position: 0 -10px;">
                            </div>
                            <div class="mb-3">
                                <label for="photo" class="form-label">Gambar</label>
                                <input type="file" class="form-control" name="photo" id="photo">
                            </div>
                        </div>
                        <div class="col-md-6">
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
                                <label for="exampleFormControlInput1" class="form-label d-block">Jenis Kelamin</label>
                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                    <input type="radio" class="btn-check" name="sex" value="1" id="sex1"
                                        {{ $employee->sex === 1 ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary" for="sex1">Laki - Laki</label>

                                    <input type="radio" class="btn-check" name="sex" value="0" id="sex2"
                                        {{ $employee->sex === 0 ? 'checked' : '' }}>
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
                            <div class="mb-3">
                                <button type="submit" class="btn btn-dark">Tambah</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
