@extends('dashboard.layouts.permit')

@section('content')
    <main class="px-5">
        <div class="mt-3 d-flex flex-column align-items-center">
            <h4 class="mb-0">SURAT KETERANGAN</h4>
            <h6 class="border-top border-2 border-dark" style="width: fit-content;">Nomor:
                {{ $number }}/SK/BPTP/KALSEL/{{ $month }}/{{ $year }}</h6>
        </div>

        <p>Yang bertanda tangan di bawah ini, Kepala Balai Pengkajian Teknologi Pertanian Kalimantan Selatan menerangkan
            bahwa:</p>

        @foreach ($employees as $employee)
            <div class="row g-0">
                <div class="col-3">Nama</div>
                <div class="col-auto">:</div>
                <div class="col ps-2">{{ $employee->name }}</div>
            </div>
            <div class="row g-0">
                <div class="col-3">NIP</div>
                <div class="col-auto">:</div>
                <div class="col ps-2">{{ $employee->nip }}</div>
            </div>
            <div class="row g-0">
                <div class="col-3">Jabatan</div>
                <div class="col-auto">:</div>
                <div class="col ps-2">{{ $employee->position }}</div>
            </div>
            <div class="row g-0">
                <div class="col-3">Status Keanggotaan</div>
                <div class="col-auto">:</div>
                <div class="col ps-2">{{ $employee->member_status }}</div>
            </div>
            <br>
        @endforeach
        <p>Yang tersebut di atas benar-benar telah melakukan pengkajian dengan judul <b>"{{ $title }}"</b></p>
        <p>Demikian surat keterangan ini disampaikan, agar dapat dipergunakan sebagaimana mestinya.</p>
    </main>
@endsection
