@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Probabilitas</h1>

</div>
<div class="card border-0 shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table id="nvby-table" class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
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
                { data: 'nama', name: 'nama' },
                { data: 'gizi', name: 'protein',
                    render : function (data,type,row) {
                        return data[1]["probabilitas"]
                } },
                { data: 'gizi', name: 'lemak',
                    render : function (data,type,row) {
                        return data[2]["probabilitas"]
                }  },

                { data: 'gizi', name: 'karbohidrat',
                    render : function (data,type,row) {
                        return data[0]["probabilitas"]
                }  },
            ]
        });
    })
</script>

@endsection
