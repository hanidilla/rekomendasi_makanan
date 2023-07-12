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
                        <th>Nama Pasien</th>
                        <th>Detail</th>
                        <th>Saran Makanan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dataRet as $key => $item)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$item['nama_pasien']}}</td>
                            <td>
                                <ul>
                                    <u>Pasien</u>
                                    <li>
                                        <b>Umur</b> : {{$item['umur']}}
                                    </li>
                                    <li>
                                        <b>Tinggi</b> : {{$item['tinggi']}}
                                    </li>
                                    <li>
                                        <b>Berat</b> : {{$item['berat']}}
                                    </li>
                                    <li>
                                        <b>Jenis Kelamin</b> : {{$item['jenis_kelamin']}}
                                    </li>
                                    <li>
                                        <b>Faktor Aktifitas</b> : {{$item['activity_fac']}}
                                    </li>
                                    <li>
                                        <b>Faktor Stress</b> : {{$item['stress_fac']}}
                                    </li>
                                </ul>
                                <ul>
                                    <u>Kebutuhan</u>
                                    <li>
                                        <b>Kalori</b> : {{$item['kalori']}}
                                    </li>
                                    <li>
                                        <b>Protein</b> : {{$item['protein']}}
                                    </li>
                                    <li>
                                        <b>Lemak</b> : {{$item['lemak']}}
                                    </li>
                                    <li>
                                        <b>Karbohidrat</b> : {{$item['karbohidrat']}}
                                    </li>
                                </ul>
                            </td>
                            <td>
                                @foreach($item['data'] as $dataKey => $dataItem)
                                <ul> <u>{{$dataKey}}</u>
                                    
                                       @foreach($dataItem as $childKey => $childItem)
                                        <li>
                                            <b>{{ucwords(str_replace('_',' ',$childKey))}}</b> : {{$childItem}}
                                        </li>
                                       @endforeach
                                    
                                </ul>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection


@section('script')

<!-- <script>
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
                            ret += `${el.bahan_makanan}, `
                        });
                        return ret
                    }
                }
            ]
        });
    })
</script> -->

@endsection
