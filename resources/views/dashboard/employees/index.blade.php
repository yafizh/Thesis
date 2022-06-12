@extends('dashboard.layouts.main')

@section('content')
    <div class="row gx-3">
        <div class="col-md-12">
            <div class="d-flex flex-column" style="height: 96.5vh;">
                <div class="d-flex mb-3">
                    <div
                        class="p-3 bg-white d-flex justify-content-center align-items-center rounded shadow flex-grow-1 h-100">
                        <h5 class="m-0 text-uppercase">Data Pegawai</h5>
                    </div>
                    <div class="ps-3 d-flex align-items-center">
                        <a href="/employees/create" class="btn btn-dark">Tambah Pegawai</a>
                    </div>
                </div>
                <div class="p-3 bg-white rounded shadow h-100">
                    <table id="example" class="table table-hover">
                        <thead>
                            <tr>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Nomor Telepon</th>
                                <th>Jabatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr data-id="{{ $employee->id }}">
                                    <td>{{ $employee->nip }}</td>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->phone_number }}</td>
                                    <td>{{ $employee->position }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Opsi Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apa yang ingin anda lakukan dengan data ini?
                </div>
                <div class="modal-footer d-flex">
                    <a id="detail" href="" class="btn btn-info" style="flex: 1 1 0;">Detail</a>
                    <a id="edit" href="" class="btn btn-warning" style="flex: 1 1 0;">Ubah</a>
                    <button id="delete" class="btn btn-danger" style="flex: 1 1 0;">Hapus</button>
                    <form action="" method="POST">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('tbody tr').each((i, obj) => $(obj).on('click', () => {
            $('#exampleModal #detail').attr('href', `/employees/${$(obj).data('id')}`);
            $('#exampleModal #edit').attr('href', `/employees/${$(obj).data('id')}/edit`);
            $('#exampleModal form').attr('action', `/employees/${$(obj).data('id')}`);
            $('#exampleModal #delete').on('click', () => {
                if (confirm('Yakin ingin menghapus data ini?')) $('#exampleModal form').submit()
            });
            $('#exampleModal').modal('show');
        }));
    </script>
@endsection
