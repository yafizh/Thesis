@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> Buku Tamu</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Buku Tamu</li>
            <li class="breadcrumb-item"><a href="#">Tambah Pengunjung</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <form action="/guests" method="POST">
                    @csrf
                    <div class="tile-body">
                        <div class="row">
                            <div class="col-xl-8 col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="control-label">NIK</label>
                                            <input class="form-control" type="text" name="nik"
                                                placeholder="Masukkan NIP" autofocus autocomplete="off"
                                                value="{{ old('nik') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Nama</label>
                                            <input class="form-control" type="text" name="name"
                                                placeholder="Masukkan Nama" autocomplete="off" value="{{ old('name') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Jenis Kelamin</label>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" value="1"
                                                        name="sex" {{ old('sex') == '1' ? 'checked' : '' }} required>Laki -
                                                    Laki
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" value="0"
                                                        name="sex" {{ old('sex') == '0' ? 'checked' : '' }} required>Perempuan
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Nomor Telepon</label>
                                            <input class="form-control" type="text" name="phone_number"
                                                placeholder="Masukkan Nomor Telepon" autocomplete="off"
                                                value="{{ old('phone_number') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Pegawai yang dikunjungi</label>
                                            <select id="employee_id" name="employee_id" placeholder="Pilih Pegawai"
                                                required></select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="control-label">Hari</label>
                                            <input class="form-control" type="text"
                                                value="{{ $DAY_IN_INDONESIA[Date('w')] }}" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Tanggal</label>
                                            <input class="form-control" type="date" value="{{ Date('Y-m-d') }}"
                                                disabled>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Jam</label>
                                            <input class="form-control" type="time" value="{{ Date('H:i') }}" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Instansi</label>
                                            <input class="form-control" type="text" name="agency"
                                                placeholder="Asal Instansi" autocomplete="off" value="{{ old('agency') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Keperluan</label>
                                            <input class="form-control" type="text" name="necessity"
                                                placeholder="Keperluan" autocomplete="off" value="{{ old('necessity') }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-12">
                                <div class="form-group text-center">
                                    <label class="control-label d-block">Gambar</label>
                                    <div class="image mb-3 d-flex justify-content-center">
                                        <video autoplay class="border" width="100%" height="100%"
                                            style="width:315px; height: 315px;"></video>
                                        <img class="d-none border" width="100%" height="100%"
                                            style="width:315px; height: 315px;">
                                        <input type="text" class="d-none" name="image">
                                    </div>
                                    <div class="actions">
                                        <button type="button" class="btn btn-success btn-sm mr-2" disabled>AMBIL
                                            ULANG</button>
                                        <button type="button" class="btn btn-success btn-sm ml-2">AMBIL GAMBAR</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tile-footer d-flex justify-content-end">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-fw fa-lg fa-check-circle"></i>
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Camera --}}
    <script>
        const hasGetUserMedia = _ => {
            return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
        }
        if (hasGetUserMedia()) {
            const constraints = {
                video: true,
                video: {
                    width: {
                        exact: 500
                    },
                    height: {
                        exact: 500
                    }
                },
            };

            const video = document.querySelector(".image video");

            navigator.mediaDevices.getUserMedia(constraints).then((stream) => {
                video.srcObject = stream;
            });

            const resetPictureBtn = document.querySelector(".actions").children[0];
            const takePictureBtn = document.querySelector(".actions").children[1];
            const img = document.querySelector(".image img");


            takePictureBtn.onclick = video.onclick = function() {
                const canvas = document.createElement("canvas");
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.getContext("2d").drawImage(video, 0, 0);
                // Other browsers will fall back to image/png
                img.src = canvas.toDataURL("image/webp");


                document.querySelector('input[name=image]').value = canvas.toDataURL("image/webp");
                video.classList.toggle('d-none');
                img.classList.toggle('d-none');
                takePictureBtn.setAttribute("disabled", "");
                resetPictureBtn.removeAttribute("disabled");
            };

            resetPictureBtn.onclick = _ => {
                document.querySelector('input[name=image]').value = '';
                video.classList.toggle('d-none');
                img.classList.toggle('d-none');
                takePictureBtn.removeAttribute("disabled");
                resetPictureBtn.setAttribute("disabled", "");
            };
        } else {
            alert("getUserMedia() is not supported by your browser");
        }
    </script>

    {{-- Tom Select --}}
    <script>
        let employees = [];
        @foreach ($employees as $employee)
            employees.push({
                id: {{ $employee->id }},
                name: '{{ $employee->name }}',
                nip: '{{ $employee->nip }}',
            });
        @endforeach

        const employee = new TomSelect("#employee_id", {
            valueField: "id",
            searchField: "name",
            options: employees,
            render: {
                option: function(data, escape) {
                    return (
                        `<div>
                        <span class='name d-block'>${escape(data.name)}</span>
                        <span class='url'>${escape(data.nip)}</span>
                        </div>`
                    );
                },
                item: function(data, escape) {
                    return (
                        `<div name='${escape(data.nip)}'>${escape(data.name)}</div>`
                    );
                },
            },
        });
        employee.setValue("{{ old('employee_id') }}");
    </script>
@endsection
