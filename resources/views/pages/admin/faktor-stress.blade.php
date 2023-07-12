@extends('layouts.app')


@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Faktor stress</h1>
        <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="btn-add-stress"><i
                class="fas fa-plus fa-sm text-white-50"></i> Tambah stress</button>
    </div>

    <div class="card border-0 shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table id="stress-tabel">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Presentase</th>
                            <th style="display: none">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-modal modalId="modal-add-stress" modalTitle="Tambah stress">
        <div class="form-group">
            <label for="">Nama stress</label>
            <input type="text" class="form-control form-control-sm" id="nama">
        </div>
        <div class="form-group">
            <label for="">Presentase</label>
            <input type="number" class="form-control form-control-sm" id="presentase">
        </div>

        <div class="d-flex justify-content-end">
            <button class="btn btn-sm btn-secondary close-modal">Tutup</button>&nbsp;
            <button class="btn btn-sm btn-primary" id="btn-save-stress">Simpan</button>
        </div>
    </x-modal>

    <x-modal modalId="modal-edit-stress" modalTitle="Edit stress">
        <input type="text" id="id_stress" hidden>
        <div class="form-group">
            <label for="">Nama stress</label>
            <input type="text" class="form-control form-control-sm" id="nama-edit">
        </div>
        <div class="form-group">
            <label for="">Presentase</label>
            <input type="number" class="form-control form-control-sm" id="presentase-edit">
        </div>

        <div class="d-flex justify-content-end">
            <button class="btn btn-sm btn-secondary close-modal">Tutup</button>&nbsp;
            <button class="btn btn-sm btn-primary" id="btn-update-stress">Simpan</button>
        </div>
    </x-modal>


@endsection


@section('script')
    <script>
        $('#stress-tabel').DataTable({
            processing: true,
            serverSide : false,
            ajax : {
                url : '/api/faktor/stress',
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
                        return ``
                    }
                }
            ]
        })

        $('#btn-add-stress').on('click', function () {
            $('#modal-add-stress').modal('show')
        })

        $('#btn-save-stress').on('click', function () {

            $.ajax({
                url: '/api/faktor/stress',
                type : 'POST',
                data : {
                    nama : $('#nama').val(),
                    presentase : $('#presentase').val(),

                },
                success : function (data) {
                    console.log(data);
                    alertSuccess("data berhasil disimpan")
                    $('#modal-add-stress').modal('hide')
                    $('#stress-tabel').DataTable().ajax.reload()
                }
            })
        })

        $('body').on('click', '.btn-edit-stress', function () {
            $('#modal-edit-stress').modal('show')
            var id = $(this).data("id")
            $.ajax({
                url : "/api/faktor/stress/" + id,
                type : "GET",
                success : function (data) {
                    console.log(data);
                    var res = data.data;
                    $("#id_stress").val(res.id)
                    $('#nama-edit').val(res.nama)
                    $('#presentase-edit').val(res.presentase)
                }
            })
        })

        $('#btn-update-stress').on("click", function () {
            $('#modal-edit-stress').modal('hide')
            var id = $('#id_stress').val()
            $.ajax({
                url : "/api/faktor/stress/" + id,
                type : "PUT",
                data : {
                    nama : $("#nama-edit").val(),
                    presentase : $("#presentase-edit").val()
                },
                success : function (data) {
                    console.log(data);
                    var res = data.data;
                    $("#stress-tabel").DataTable().ajax.reload()
                    alertSuccess("Sukses");
                }
            })
        })

        $('body').on('click', '.btn-delete-stress', function () {
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
                        url: `/api/faktor/stress/${id}`,
                        type : 'DELETE',
                        success : function (data) {
                            console.log(data);
                            alertSuccess("data berhasil dihapus")
                            $('#stress-tabel').DataTable().ajax.reload()
                        }
                    })
                }
            })
        })

    </script>
@endsection
