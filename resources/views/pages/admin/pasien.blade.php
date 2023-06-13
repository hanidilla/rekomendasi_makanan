@extends('layouts.app')


@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            List Pasien
        </h1>
        <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="btn-add-pasien"><i
                class="fas fa-plus fa-sm text-white-50"></i> Tambah Pasien</button>
    </div>

    <div class="card border-0 shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table id="pasien-tabel">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Umur</th>
                            <th>Jenis Kelamin</th>
                            <th>Tinggi Badan</th>
                            <th>Berat Badan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-modal modalId="modal-add-pasien" modalTitle="Tambah pasien">
        <div class="form-group">
            <label for="">Nama pasien</label>
            <input type="text" class="form-control form-control-sm" id="nama">
        </div>
        <div class="form-group">
            <label for="">umur</label>
            <input type="number" class="form-control form-control-sm" id="umur">
        </div>
        <div class="form-group">
            <label for="">Jenis Kelamin</label>
            <select name="" id="jenis-kelamin" class="form-control form-control-sm">
                <option value="laki-laki">Laki-laki</option>
                <option value="perempuan">Perempuan</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">Tinggi Badan</label>
            <input type="number" class="form-control form-control-sm" id="tinggi-badan">
        </div>
        <div class="form-group">
            <label for="">Berat Badan</label>
            <input type="number" class="form-control form-control-sm" id="berat-badan">
        </div>


        <div class="d-flex justify-content-end">
            <button class="btn btn-sm btn-secondary close-modal">Tutup</button>&nbsp;
            <button class="btn btn-sm btn-primary" id="btn-save-pasien">Simpan</button>
        </div>
    </x-modal>


    <x-modal modalId="modal-edit-pasien" modalTitle="Edit pasien">
        <input type="text" hidden id="id_pasien">
        <div class="form-group">
            <label for="">Nama pasien</label>
            <input type="text" class="form-control form-control-sm" id="nama">
        </div>
        <div class="form-group">
            <label for="">umur</label>
            <input type="number" class="form-control form-control-sm" id="umur">
        </div>
        <div class="form-group">
            <label for="">Jenis Kelamin</label>
            <select name="" id="jenis-kelamin" class="form-control form-control-sm">
                <option value="laki-laki">Laki-laki</option>
                <option value="perempuan">Perempuan</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">Tinggi Badan</label>
            <input type="number" class="form-control form-control-sm" id="tinggi-badan">
        </div>
        <div class="form-group">
            <label for="">Berat Badan</label>
            <input type="number" class="form-control form-control-sm" id="berat-badan">
        </div>


        <div class="d-flex justify-content-end">
            <button class="btn btn-sm btn-secondary close-modal">Tutup</button>&nbsp;
            <button class="btn btn-sm btn-primary" id="btn-update-pasien">Simpan</button>
        </div>
    </x-modal>
@endsection


@section('script')
    <script>
        $('#pasien-tabel').DataTable({
            processing: true,
            serverSide : false,
            ajax : {
                url : '/api/pasien',
                type: "GET"
            },
            columns : [
                {
                    data : 'id'
                },
                {
                    data : 'nama'
                },{
                    data : 'umur'
                },{
                    data : 'jenis_kelamin'
                },{
                    data : 'tinggi_badan'
                },{
                    data : 'berat_badan'
                },{
                    data : 'id',
                    render : function (data) {
                        return `<button class="btn btn-sm btn-warning btn-edit-pasien" data-id=${data}><i class="fas fa-pencil-alt"></i></button>
                                <button class="btn btn-sm btn-danger btn-delete-pasien" data-id=${data}><i class="fas fa-trash"></i></button>`
                    }
                }
            ]
        })

        $('#btn-add-pasien').on('click', function () {
            $('#modal-add-pasien').modal('show')
        })

        $('#btn-save-pasien').on('click', function () {

            $.ajax({
                url: '/api/pasien',
                type : 'POST',
                data : {
                    nama : $('#nama').val(),
                    umur : $('#umur').val(),
                    jenis_kelamin : $('#jenis-kelamin').val(),
                    tinggi_badan: $('#tinggi-badan').val(),
                    berat_badan : $('#berat-badan').val(),
                },
                success : function (data) {
                    console.log(data);
                    alertSuccess("data berhasil disimpan")
                    $('#modal-add-pasien').modal('hide')
                    $('#pasien-tabel').DataTable().ajax.reload()
                }
            })
        })


        $('#pasien-tabel').on('click', '.btn-edit-pasien', function () {
            let id = $(this).data('id')
            $.ajax({
                url: `/api/pasien/${id}`,
                type : 'GET',
                success : function (res) {
                    var data = res.data;
                    console.log(data);
                    $('#modal-edit-pasien').modal('show')
                    $('#modal-edit-pasien #id_pasien').val(data.id)
                    $('#modal-edit-pasien #nama').val(data.nama)
                    $('#modal-edit-pasien #umur').val(data.umur)
                    $('#modal-edit-pasien #jenis-kelamin').val(data.jenis_kelamin)
                    $('#modal-edit-pasien #tinggi-badan').val(data.tinggi_badan)
                    $('#modal-edit-pasien #berat-badan').val(data.berat_badan)

                }
            })
        })

        $('#btn-update-pasien').on('click', function () {
            let id = $('#modal-edit-pasien #id_pasien').val()
            $.ajax({
                url: `/api/pasien/${id}`,
                type : 'PUT',
                data : {
                    nama : $('#modal-edit-pasien #nama').val(),
                    umur : $('#modal-edit-pasien #umur').val(),
                    jenis_kelamin : $('#modal-edit-pasien #jenis-kelamin').val(),
                    tinggi_badan: $('#modal-edit-pasien #tinggi-badan').val(),
                    berat_badan : $('#modal-edit-pasien #berat-badan').val(),
                },
                success : function (data) {
                    console.log(data);
                    alertSuccess("data berhasil diupdate")
                    $('#modal-edit-pasien').modal('hide')
                    $('#pasien-tabel').DataTable().ajax.reload()
                }
            })
        })

        $('#pasien-tabel').on('click', '.btn-delete-pasien', function () {
            let id = $(this).data('id')
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
                        url: `/api/pasien/${id}`,
                        type : 'DELETE',
                        success : function (data) {
                            console.log(data);
                            alertSuccess("data berhasil dihapus")
                            $('#pasien-tabel').DataTable().ajax.reload()
                        }
                    })
                }
            })
        })

    </script>
@endsection
