@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Bahan Makanan</h1>
    <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="btn-add-gizi"><i
            class="fas fa-plus fa-sm text-white-50"></i> Tambah Bahan</button>
</div>

<div class="card border-0 shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table id="gizi-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kebutuhan Kalori (kal)</th>
                        <th>Protein (gr)</th>
                        <th>Lemak (gr)</th>
                        <th>Karbohidrat (gr)</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>



<x-modal modalId="modal-add-gizi" modalTitle="Tambah gizi">
    <div class="form-group">
        <label for="">Pasien</label>
        {{-- <input type="text" class="form-control form-control-sm" id="nama_bahan"> --}}
        <select name="" class="form-control" id="list-pasien">
            <option value="1">Aku</option>
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
    <div class="form-group">
        <label for="">Umur</label>
        <input type="number" class="form-control form-control-sm" id="umur">
    </div>
    <div class="form-group">
        <label for="">Faktor Aktifitas</label>
        <select name="" id="faktor-aktifitas" class="form-control"></select>
    </div>
    <div class="form-group">
        <label for="">Faktor Stress</label>
        <select name="" id="faktor-stress" class="form-control"></select>
    </div>

    <div class="d-flex justify-content-end">
        <button class="btn btn-sm btn-secondary close-modal">Tutup</button>&nbsp;
        <button class="btn btn-sm btn-primary" id="btn-save-gizi">Simpan</button>
    </div>
</x-modal>

@endsection


@section('script')

<script>
    $(document).ready(function () {
        $('#gizi-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: '/api/kebutuhan-gizi',
            columns: [
                { data: 'id', name: 'DT_RowIndex' },
                { data: 'pasien.nama', name: 'nama' },
                { data: 'kalori', name: 'kebutuhan_kalori' },
                { data: 'protein', name: 'protein' },
                { data: 'lemak', name: 'lemak' },
                { data: 'karbohidrat', name: 'karbohidrat' },
            ]
        });
    })

    $('#btn-add-gizi').on('click', function(){
        $('#modal-add-gizi').modal('show')
        $.ajax({
            url : '/api/all-factor',
            method : 'GET',
            success : function(res){
                console.log(res)
                let faktorAktifitas = res.data.faktor_aktivitas
                let faktorStress = res.data.faktor_stress
                $('#faktor-aktifitas').empty()
                $('#faktor-stress').empty()
                faktorAktifitas.forEach(function(item){
                    $('#faktor-aktifitas').append(`
                        <option value="${item.presentase}">${item.nama}</option>
                    `)
                })

                faktorStress.forEach(function(item){
                    $('#faktor-stress').append(`
                        <option value="${item.presentase}">${item.nama}</option>
                    `)
                })
            }
        })

        $.ajax({
            url : '/api/pasien',
            type : "GET",
            success : function (res) {
                // console.log(res);
               var data = res.data;
               $('#list-pasien').empty().prepend(`<option selected disabled>--- Pilih Pasien ---</option>`);
               data.forEach(el => {
                    $('#list-pasien').append(`<option value=${el.id}>${el.nama} </option>`)
               });
            }
        })
    })

    $('#list-pasien').on("change", function () {
        $.ajax({
            url : '/api/pasien/' + $(this).val(),
            type : 'GET',
            success : function (data) {
                console.log(data);
                var res = data.data;
                $('#tinggi-badan').val(res.tinggi_badan).attr("disabled", true)
                $('#berat-badan').val(res.berat_badan).attr("disabled", true)
                $('#umur').val(res.umur).attr("disabled", true)
            }
        })
    })

    $('#btn-save-gizi').on('click', function(){
        $.ajax({
            url : '/api/kebutuhan-gizi',
            method : 'POST',
            data : {
                user_id : $('#list-pasien').val(),
                tinggi : $('#tinggi-badan').val(),
                berat : $('#berat-badan').val(),
                umur : $('#umur').val(),
                activity_fac : $('#faktor-aktifitas').val(),
                stress_fac : $('#faktor-stress').val()
            },
            success :function (data){
                console.log(data)
                $('#modal-add-gizi').modal('hide')
                $('#gizi-table').DataTable().ajax.reload()
            }
        })
    })
</script>

@endsection
