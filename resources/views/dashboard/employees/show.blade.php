@extends('dashboard.layouts.main')

@section('content')
<div class="row gx-3" style="height: 96.5vh;">
        <div class="col-md-5">
            <div class="p-3 text-center bg-white rounded shadow h-100 d-flex flex-column">
                <div class="employee-image rounded-circle mb-3 align-self-center">
                    <img src="{{ asset('storage/' . $employee->photo) }}" class="rounded-circle">
                </div>
                <div class="mb-5">
                    <h4>{{ $employee->name }}</h4>
                    <h5>{{ $employee->nip }}</h5>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6>Memimpin Penelitian</h6>
                        <h4>15</h4>
                    </div>
                    <div class="col-md-6">
                        <h6>Menerima Kunjungan</h6>
                        <h4>15</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6>Melakukan Penelitian</h6>
                        <h4>15</h4>
                    </div>
                    <div class="col-md-6">
                        <h6>Melakukan Pengkajian</h6>
                        <h4>15</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="bg-white rounded shadow p-3 h-100">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                            type="button" role="tab" aria-controls="nav-home" aria-selected="true">Profil</button>
                        <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                            aria-selected="false">Penelitian</button>
                        <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                            aria-selected="false">Pengkajian</button>
                        <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                            aria-selected="false">Menerima Kunjungan</button>
                    </div>
                </nav>
                <div class="tab-content p-2" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"
                        tabindex="0">
                        <div class="mb-3">
                            <label for="position" class="form-label">Jabatan</label>
                            <input type="text" class="form-control" id="position" value="{{ $employee->position }}"
                                readonly>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="phone_number"
                                value="{{ $employee->phone_number }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="sex" class="form-label">Jenis Kelamin</label>
                            <input type="text" class="form-control" id="sex"
                                value="{{ $employee->sex ? 'Laki - Laki' : 'Perempuan' }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea id="address" class="form-control" readonly>{{ $employee->address }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Pengaturan</label>
                            <div class="d-flex gap-3">
                                <a href="" class="btn btn-primary btn-sm form-control">Ganti Password</a>
                                <a href="/employees/{{ $employee->id }}/edit"
                                    class="btn btn-warning btn-sm form-control">Edit Profil</a>

                                {{-- Delete Action --}}
                                <button class="btn btn-danger btn-sm form-control">Hapus Pegawai</button>
                                <form action="/employees/{{ $employee->id }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                {{-- End Delete Action --}}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"
                        tabindex="0">
                        ...</div>
                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab"
                        tabindex="0">
                        ...</div>
                    <div class="tab-pane fade" id="nav-disabled" role="tabpanel" aria-labelledby="nav-disabled-tab"
                        tabindex="0">...</div>
                </div>
            </div>
        </div>
    </div>


    <style>
        .employee-image {
            position: relative;
            width: 100%;
            max-width: 18rem;
        }

        .employee-image:after {
            content: "";
            display: block;
            padding-bottom: 100%;
            /* The padding depends on the width, not on the height, so with a padding-bottom of 100% you will get a square */
        }

        .employee-image img {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: 0 -25px;
            /* object-position: center; */
        }
    </style>
@endsection
