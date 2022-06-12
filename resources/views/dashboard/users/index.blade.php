@extends('dashboard.layouts.main')

@section('content')
    <div class="row gx-3">
        <div class="col-md-12">
            <div class="d-flex flex-column" style="height: 96.5vh;">
                <div class="d-flex mb-3">
                    <div
                        class="p-3 bg-white d-flex justify-content-center align-items-center rounded shadow flex-grow-1 h-100">
                        <h5 class="m-0 text-uppercase">Data User</h5>
                    </div>
                    <div class="ps-3 d-flex align-items-center">
                        <a href="/users/create" class="btn btn-dark">Tambah User</a>
                    </div>
                </div>
                <div class="p-3 bg-white rounded shadow h-100">
                    <table id="example" class="table table-hover">
                        <thead>
                            <tr>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr data-id="{{ $user->id }}">
                                    <td>{{ $user->employee->nip }}</td>
                                    <td>{{ $user->employee->name }}</td>
                                    <td>{{ $user->status }}</td>
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
                    <h5 class="modal-title" id="exampleModalLabel">Opsi User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apa yang ingin anda lakukan dengan data ini?
                </div>
                <div class="modal-footer d-flex">
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
            $('#exampleModal form').attr('action', `/users/${$(obj).data('id')}`);
            $('#exampleModal #delete').on('click', () => {
                if (confirm('Yakin ingin menghapus data ini?')) $('#exampleModal form').submit()
            });
            $('#exampleModal').modal('show');
        }));
    </script>
@endsection
