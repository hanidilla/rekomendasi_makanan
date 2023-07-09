@extends('layouts.app')


@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Bahan Makanan</h1>
        <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="btn-add-bahan"><i
                class="fas fa-plus fa-sm text-white-50"></i> Tambah Bahan</button>
    </div>

    <div class="card border-0 shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table id="makanan-tabel">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bahan Makanan</th>
                            <th>Berat (gr)</th>
                            <th>Energi (kal)</th>
                            <th>Protein (gr)</th>
                            <th>Lemak (gr)</th>
                            <th>Karbohidrat (gr)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-modal modalId="modal-add-bahan" modalTitle="Tambah Bahan">
        <div class="form-group">
            <label for="">Nama Bahan</label>
            <input type="text" class="form-control form-control-sm" id="nama_bahan">
        </div>
        <div class="form-group">
            <label for="">Berat</label>
            <input type="number" class="form-control form-control-sm" id="berat">
        </div>
        <div class="form-group">
            <label for="">Energi</label>
            <input type="number" class="form-control form-control-sm" id="energi">
        </div>
        <div class="form-group">
            <label for="">Protein</label>
            <input type="number" class="form-control form-control-sm" id="protein">
        </div>
        <div class="form-group">
            <label for="">Lemak</label>
            <input type="number" class="form-control form-control-sm" id="lemak">
        </div>
        <div class="form-group">
            <label for="">Karbohidrat</label>
            <input type="number" class="form-control form-control-sm" id="karbohidrat">
        </div>

        <div class="d-flex justify-content-end">
            <button class="btn btn-sm btn-secondary close-modal">Tutup</button>&nbsp;
            <button class="btn btn-sm btn-primary" id="btn-save-bahan">Simpan</button>
        </div>
    </x-modal>

    <x-modal modalId="modal-edit-bahan" modalTitle="Edit Bahan">
        <input type="hidden" id="id_bahan">
        <div class="form-group">
            <label for="">Nama Bahan</label>
            <input type="text" class="form-control form-control-sm" id="nama_bahan-edit">
        </div>
        <div class="form-group">
            <label for="">Berat</label>
            <input type="number" class="form-control form-control-sm" id="berat-edit">
        </div>
        <div class="form-group">
            <label for="">Energi</label>
            <input type="number" class="form-control form-control-sm" id="energi-edit">
        </div>
        <div class="form-group">
            <label for="">Protein</label>
            <input type="number" class="form-control form-control-sm" id="protein-edit">
        </div>
        <div class="form-group">
            <label for="">Lemak</label>
            <input type="number" class="form-control form-control-sm" id="lemak-edit">
        </div>
        <div class="form-group">
            <label for="">Karbohidrat</label>
            <input type="number" class="form-control form-control-sm" id="karbohidrat-edit">
        </div>

        <div class="d-flex justify-content-end">
            <button class="btn btn-sm btn-secondary close-modal">Tutup</button>&nbsp;
            <button class="btn btn-sm btn-primary" id="btn-update-bahan">Simpan</button>
        </div>
    </x-modal>


@endsection


@section('script')
    <script>
        $('#makanan-tabel').DataTable({
            processing: true,
            serverSide : false,
            ajax : {
                url : '/api/bahan-makanan',
                type: "GET"
            },
            columns : [
                {
                    data : 'id'
                },
                {
                    data : 'bahan_makanan'
                },{
                    data : 'berat'
                },{
                    data : 'energi'
                },{
                    data : 'protein'
                },{
                    data : 'lemak'
                },{
                    data : 'karbohidrat'
                },{
                    data : 'id',
                    render : function (data) {
                        return `<button class="btn btn-sm btn-warning btn-edit-bahan" data-id=${data}><i class="fas fa-pencil-alt"></i></button>
                                <button class="btn btn-sm btn-danger btn-delete-bahan" data-id=${data}><i class="fas fa-trash"></i></button>`
                    }
                }
            ]
        })

        $('#btn-add-bahan').on('click', function () {
            $('#modal-add-bahan').modal('show')
        })

        $('#btn-save-bahan').on('click', function () {

            $.ajax({
                url: '/api/bahan-makanan',
                type : 'POST',
                data : {
                    bahan_makanan : $('#nama_bahan').val(),
                    berat : $('#berat').val(),
                    energi : $('#energi').val(),
                    protein: $('#protein').val(),
                    lemak : $('#lemak').val(),
                    karbohidrat : $('#karbohidrat').val()
                },
                success : function (data) {
                    console.log(data);
                    alertSuccess("data berhasil disimpan")
                    $('#modal-add-bahan').modal('hide')
                    $('#makanan-tabel').DataTable().ajax.reload()
                }
            })
        })

        $(document).on('click', '.btn-edit-bahan', function () {
            let id = $(this).data('id')

            $.ajax({
                url : `/api/bahan-makanan/${id}`,
                type : 'GET',
                success : function (res) {
                    var data = res.data
                    console.log("ADTA", data);
                    $('#modal-edit-bahan').modal('show')
                    // $('#modal-edit-bahan').find('.modal-title').text('Edit Bahan')
                    $('#id_bahan').val(data.id)
                    $('#nama_bahan-edit').val(data.bahan_makanan)
                    $('#berat-edit').val(data.berat)
                    $('#energi-edit').val(data.energi)
                    $('#protein-edit').val(data.protein)
                    $('#lemak-edit').val(data.lemak)
                    $('#karbohidrat-edit').val(data.karbohidrat)

                }
            })
        })

        $('#btn-update-bahan').on('click', function () {
            let id = $('#id_bahan').val()

            $.ajax({
                url : `/api/bahan-makanan/${id}`,
                type : 'PUT',
                data : {
                    bahan_makanan : $('#nama_bahan-edit').val(),
                    berat : $('#berat-edit').val(),
                    energi : $('#energi-edit').val(),
                    protein: $('#protein-edit').val(),
                    lemak : $('#lemak-edit').val(),
                    karbohidrat : $('#karbohidrat-edit').val()
                },
                success : function (data) {
                    alertSuccess('Data berhasil diupdate')
                    $('#modal-edit-bahan').modal('hide')
                    $('#makanan-tabel').DataTable().ajax.reload()
                }
            })
        })


        $(document).on('click', '.btn-delete-bahan', function () {
            let id = $(this).data('id')

           Swal.fire({
               title : 'Apakah anda yakin?',
               text : 'Data akan dihapus',
               icon : 'warning',
               showCancelButton : true,
               confirmButtonText : 'Ya, hapus',
               cancelButtonText : 'Tidak'
           }).then((result) => {
               if (result.isConfirmed) {
                   $.ajax({
                       url : `/api/bahan-makanan/${id}`,
                       type : 'DELETE',
                       success : function (data) {
                           alertSuccess('Data berhasil dihapus')
                           $('#makanan-tabel').DataTable().ajax.reload()
                       }
                   })
               }
           })
        })

    </script>
@endsection
