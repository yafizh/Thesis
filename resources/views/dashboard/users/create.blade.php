@extends('dashboard.layouts.main')

@section('content')
    <div class="row gx-3">
        <div class="col-md-6">
            <div class="d-flex flex-column" style="height: 96.5vh;">
                <div class="d-flex mb-3">
                    <div
                        class="p-3 bg-white d-flex justify-content-center align-items-center rounded shadow flex-grow-1 h-100">
                        <h5 class="m-0 text-uppercase">Tambah Data User</h5>
                    </div>
                </div>
                <form class="h-100" action="/users" method="POST">
                    @csrf
                    <div class="p-3 h-100 bg-white pb-5 rounded shadow" style="position: relative;">
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Pilih Pegawai</label>
                            <select id="employee_id" name="employee_id" placeholder="Pilih Pegawai..."></select>
                            <script>
                                let data = [];
                                @foreach ($employees as $employee)
                                    data.push({
                                        id: {{ $employee->employee_id }},
                                        name: '{{ $employee->name }}',
                                        nip: '{{ $employee->nip }}',
                                    });
                                @endforeach

                                const employee = new TomSelect("#employee_id", {
                                    valueField: "id",
                                    searchField: "name",
                                    options: data,
                                    render: {
                                        option: function(data, escape) {
                                            return (
                                                `<div>
                                                <span class='name'>${escape(data.name)}</span>
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
                                employee.setValue('{{ old('employee_id') }}');
                            </script>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Pilih Status</label>
                            <select id="status" name="status" placeholder="Pilih Status..." autocomplete="off">
                                <option value="">Pilih Status...</option>
                                <option value="admin">Admin</option>
                                <option value="receptionist">Resepsionis</option>
                            </select>
                            <script>
                                const status = new TomSelect("#status");
                                status.setValue('{{ old('status') }}');
                            </script>
                        </div>
                        <div class="mb-3" style="position: absolute; bottom: 0; right: 1rem;">
                            <button type="submit" class="btn btn-dark">Tambah</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
