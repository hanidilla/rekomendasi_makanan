@extends('layouts.app')


@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Faktor Aktifitas</h1>
        <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="btn-add-aktifitas"><i
                class="fas fa-plus fa-sm text-white-50"></i> Tambah aktifitas</button>
    </div>

    <div class="card border-0 shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table id="aktifitas-tabel">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Presentase</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-modal modalId="modal-add-aktifitas" modalTitle="Tambah aktifitas">
        <div class="form-group">
            <label for="">Nama aktifitas</label>
            <input type="text" class="form-control form-control-sm" id="nama">
        </div>
        <div class="form-group">
            <label for="">Presentase</label>
            <input type="number" class="form-control form-control-sm" id="presentase">
        </div>

        <div class="d-flex justify-content-end">
            <button class="btn btn-sm btn-secondary close-modal">Tutup</button>&nbsp;
            <button class="btn btn-sm btn-primary" id="btn-save-aktifitas">Simpan</button>
        </div>
    </x-modal>

    <x-modal modalId="modal-edit-aktifitas" modalTitle="Edit aktifitas">
        <input type="text" id="id_aktifitas" hidden>
        <div class="form-group">
            <label for="">Nama aktifitas</label>
            <input type="text" class="form-control form-control-sm" id="nama-edit">
        </div>
        <div class="form-group">
            <label for="">Presentase</label>
            <input type="number" class="form-control form-control-sm" id="presentase-edit">
        </div>

        <div class="d-flex justify-content-end">
            <button class="btn btn-sm btn-secondary close-modal">Tutup</button>&nbsp;
            <button class="btn btn-sm btn-primary" id="btn-update-aktifitas">Simpan</button>
        </div>
    </x-modal>


@endsection


@section('script')
    <script>
        $('#aktifitas-tabel').DataTable({
            processing: true,
            serverSide : false,
            ajax : {
                url : '/api/faktor/aktifitas',
                type: "GET"
            },
            columns : [
                {
                    data : 'id'
                },
                {
                    data : 'nama'
                }
                ,
                {
                    data : 'presentase'
                }
                ,{
                    data : 'id',
                    render : function (data) {
                        return `<button class="btn btn-sm btn-warning btn-edit-aktifitas" data-id=${data}><i class="fas fa-pencil-alt"></i></button>
                                <button class="btn btn-sm btn-danger btn-delete-aktifitas" data-id=${data}><i class="fas fa-trash"></i></button>`
                    }
                }
            ]
        })

        $('#btn-add-aktifitas').on('click', function () {
            $('#modal-add-aktifitas').modal('show')
        })

        $('#btn-save-aktifitas').on('click', function () {
            $.ajax({
                url: '/api/faktor/aktifitas',
                type : 'POST',
                data : {
                    nama : $('#nama').val(),
                    presentase : $('#presentase').val(),

                },
                success : function (data) {
                    console.log(data);
                    alertSuccess("data berhasil disimpan")
                    $('#modal-add-aktifitas').modal('hide')
                    $('#aktifitas-tabel').DataTable().ajax.reload()
                }
            })
        })


        $('body').on('click', '.btn-edit-aktifitas', function () {
            $('#modal-edit-aktifitas').modal('show')
            var id = $(this).data("id")
            $.ajax({
                url : "/api/faktor/aktifitas/" + id,
                type : "GET",
                success : function (data) {
                    console.log(data);
                    var res = data.data;
                    $("#id_aktifitas").val(res.id)
                    $('#nama-edit').val(res.nama)
                    $('#presentase-edit').val(res.presentase)
                }
            })
        })

        $('#btn-update-aktifitas').on("click", function () {
            $('#modal-edit-aktifitas').modal('hide')
            var id = $('#id_aktifitas').val()
            $.ajax({
                url : "/api/faktor/aktifitas/" + id,
                type : "PUT",
                data : {
                    nama : $("#nama-edit").val(),
                    presentase : $("#presentase-edit").val()
                },
                success : function (data) {
                    console.log(data);
                    var res = data.data;
                    $("#aktifitas-tabel").DataTable().ajax.reload()
                    alertSuccess("Sukses");
                }
            })
        })

        $('body').on('click', '.btn-delete-aktifitas', function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',

                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/api/faktor/aktifitas/${id}`,
                        type : 'DELETE',
                        success : function (data) {
                            console.log(data);
                            alertSuccess("data berhasil dihapus")
                            $('#aktifitas-tabel').DataTable().ajax.reload()
                        }
                    })
                }
            })
        })


    </script>
@endsection
