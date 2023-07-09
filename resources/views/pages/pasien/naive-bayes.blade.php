@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Saran Makanan</h1>

</div>
<div class="card border-0 shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table id="nvby-table" class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kebutuhan Gizi ID</th>
                        <th>Saran Makanan</th>

                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection


@section('script')

<script>
    $(document).ready(function () {
        $.ajax({
            url : "/api/nv-bayes/get-data",
            type : "GET",
            success : function (data) {
                console.log(data);
            }
        })
        $('#nvby-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: '/api/nv-bayes/get-data',
            columns: [
                { data: 'id', name: 'DT_RowIndex' ,render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }},
                { data: 'pasien.id', name: 'nama' },
                { data: 'saran_makanan', name: 'protein',
                    render : function (data,type,row) {
                        var ret = ''
                        data.forEach(el => {
                            ret += `<li>${el.bahan_makanan}</li>`
                        });
                        return ret
                    }
                }
            ]
        });
    })
</script>

@endsection
