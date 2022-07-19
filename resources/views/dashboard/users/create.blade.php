@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> Form Pengguna</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Pengguna</li>
            <li class="breadcrumb-item"><a href="#">Tambah Pengguna</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="tile">
                <form action="/users" method="POST">
                    @csrf
                    <h3 class="tile-title">Data Pengguna</h3>
                    <div class="tile-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="control-label">NIP</label>
                                    <input class="form-control" type="text" name="nip" readonly required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Nama</label>
                                    <select id="name" name="name" placeholder="Pilih Pegawai" required></select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Username</label>
                                    <input class="form-control" type="text" name="username" readonly required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Password</label>
                                    <input class="form-control" type="password" name="password"
                                        placeholder="Masukkan Password" autocomplete="off" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Status</label>
                                    <select class="form-control" name="status" required>
                                        <option value="" selected disabled>Pilih Status</option>
                                        <option value="ADMIN">Admin</option>
                                        <option value="RECEPTIONIST">Resepsionis</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tile-footer d-flex justify-content-end">
                        <a class="btn btn-secondary" href="/users">
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
    <script>
        let users = [];
        @foreach ($users as $user)
            users.push({
                id: {{ $user->id }},
                name: '{{ $user->name }}',
                username: '{{ $user->username }}',
            });
        @endforeach

        const user = new TomSelect("#name", {
            valueField: "id",
            searchField: "name",
            options: users,
            render: {
                option: function(data, escape) {
                    return (
                        `<div>
                        <span class='name d-block'>${escape(data.name)}</span>
                        <span class='url'>${escape(data.username)}</span>
                        </div>`
                    );
                },
                item: function(data, escape) {
                    document.querySelector('input[name=nip]').value = data.username;
                    document.querySelector('input[name=username]').value = data.username;
                    return (
                        `<div name='${escape(data.username)}'>${escape(data.name)}</div>`
                    );
                },
            },
        });
        employee.setValue('{{ old('employee_id') }}');
    </script>
@endsection
