@extends('dashboard.layouts.main')

@section('app-content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> Form Pengajuan Proposal</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Proposal</li>
            <li class="breadcrumb-item"><a href="#">Proposal Penelitian</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="tile mb-0">
                <form action="/{{ explode('.', Route::currentRouteName())[0] }}/{{ $proposal->research_id }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <h3 class="tile-title">Data Proposal Penelitian</h3>
                    <div class="tile-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="control-label">NIP Penanggung Jawab</label>
                                    <input class="form-control" type="text" value="{{ $proposal->head->nip }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Nama Penanggung Jawab</label>
                                    <input class="form-control" type="text" value="{{ $proposal->head->name }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Judul Penelitian</label>
                                    <input class="form-control" type="text" name="title"
                                        placeholder="Masukkan Judul Penelitian..."
                                        value="{{ old('title', $proposal->title) }}" required>
                                </div>
                                <h6>Anggaran</h6>
                                <div id="budged" class="row">
                                    @foreach ($proposal->budgets as $budget)
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="control-label">Keperluan</label>
                                                <input type="text" name="name[]" class="form-control"
                                                    oninput="addField()" value="{{ $budget->name }}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="control-label">Anggaran</label>
                                                <input type="text" name="cost[]" class="form-control"
                                                    oninput="addField()" value="{{ $budget->cost }}">
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="control-label">Keperluan</label>
                                            <input type="text" name="name[]" class="form-control" oninput="addField()" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="control-label">Anggaran</label>
                                            <input type="text" name="cost[]" class="form-control" oninput="addField()" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Anggota Penelitian</label>
                                    <select id="research_member" name="research_member[]" multiple
                                        placeholder="Pilih Pegawai"></select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Anggota Penyuluh</label>
                                    <select id="extensionists_member" name="extensionists_member[]" multiple
                                        placeholder="Pilih Pegawai"></select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">File Proposal</label>
                                    <div class="custom-file">
                                        <input type="file" name="proposal" class="custom-file-input"
                                            onchange="preview(this)" accept=".pdf">
                                        <label class="custom-file-label">Pilih file baru untuk memperbaharui Proposal</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        const addField = () => {
                            var container = document.getElementById("budged");
                            let x = true;
                            const input1 = document.querySelectorAll("input[name='name[]']");
                            const input2 = document.querySelectorAll("input[name='cost[]']");
                            const input3 = [...input1, ...input2];
                            input3.forEach((elm) => {
                                if (!(elm.value).trim()) x = false;
                            });

                            if (x) {
                                container.insertAdjacentHTML("beforeend", `
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="control-label">Keperluan</label>
                                            <input type="text" name="name[]" class="form-control" oninput="addField()">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="control-label">Anggaran</label>
                                            <input type="text" name="cost[]" class="form-control" oninput="addField()">
                                        </div>
                                    </div>
                                `.trim());
                            }
                        }
                    </script>
                    <div class="tile-footer d-flex justify-content-between">
                        <a class="btn btn-secondary" href="{{ url()->previous() }}">
                            <i class="fa fa-wa fa-lg fa-arrow-circle-left"></i>
                            Kembali
                        </a>
                        &nbsp;&nbsp;&nbsp;
                        <button class="btn btn-primary" type="submit"
                            onclick="return confirm('Yakin ingin memperbaharui pengajuan proposal?')">
                            <i class="fa fa-fw fa-lg fa-check-circle"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <iframe id="preview-proposal" src="{{ asset('storage/' . $proposal->file) }}" width="100%" height="100%"
                frameborder="0"></iframe>
        </div>
    </div>
    <script>
        function preview(input) {
            const iframe = document.querySelector(`#preview-${input.getAttribute('name')}`);
            iframe.src = URL.createObjectURL(input.files[0]);
            input.nextElementSibling.innerHTML = input.files[0].name;
        }

        // Dibikin 2 walaupun isinya sama, karena error kalau hanya satu
        let research_employees = [];
        let extensionists_employees = [];
        @foreach ($employees as $employee)
            research_employees.push({
                id: {{ $employee->id }},
                name: '{{ $employee->name }}',
                nip: '{{ $employee->nip }}',
            });
        @endforeach
        @foreach ($employees as $employee)
            extensionists_employees.push({
                id: {{ $employee->id }},
                name: '{{ $employee->name }}',
                nip: '{{ $employee->nip }}',
            });
        @endforeach

        const research_member = new TomSelect("#research_member", {
            valueField: "id",
            searchField: "name",
            options: research_employees,
            plugins: ['remove_button'],
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
        @foreach (old('research_member', $proposal->research_member) as $employee)
            research_member.addItem('{{ $employee }}');
        @endforeach


        const extensionists_member = new TomSelect("#extensionists_member", {
            valueField: "id",
            searchField: "name",
            options: extensionists_employees,
            plugins: ['remove_button'],
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
        @foreach (old('extensionists_member', $proposal->extensionists_member) as $employee)
            extensionists_member.addItem('{{ $employee }}');
        @endforeach
    </script>
@endsection
