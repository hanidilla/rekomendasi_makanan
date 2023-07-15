@extends('layouts.app')

@section('content')
<div class="align-items-center mb-4" >
    <div class="row">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-gray-800">Saran Makanan</h1>
        </div>
        <div class="col-md-6">
            <form action="{{url('/nv-bayes')}}">
                <div class="input-group">
                    <input type="text" class="form-control " placeholder="Cari Kode Pasien Atau Kode Kebutuhan Pasien"
                                    aria-label="Search" required name="kode" aria-describedby="basic-addon2" value="{{$request->kode}}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div> 
    </div>
</div>
<div class="card border-0 shadow">
    <div class="card-header">
         <p align="center">
            Hasil Yang Ditemukan : {{count($dataRet)}}
        </p>
    </div>
    <div class="card-body">
        
        
            @foreach($dataRet as $key => $item)
            <div class="row">
                <div class="col-md-6">
                    <ul>
                        <u><h4>Pasien</h4></u>
                            <li>
                                <b>Nama</b> : {{$item['nama_pasien']}}
                            </li>
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
                 </div>
                 <div class="col-md-6">
                    <ul>
                        <u><h4>Kebutuhan</h4></u>
                        <li>
                            <b>Kode Pasien</b> : {{$item['kode_pasien']}}
                        </li>
                         <li>
                            <b>Kode Kebutuhan</b> : {{$item['kode_kebutuhan']}}
                        </li>
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
                        <li>
                            <b>Created At</b> : {{$item['created_at']}}
                        </li>
                    </ul>
                </div>
           
            <div class="table-responsive">
                <table id="nvby-table" class="table">
                    <thead>
                        <tr>
                            <th style="text-align: center;">No</th>
                            <th style="text-align: center;" >Probabilitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                            <tr>
                                <td style="text-align: center;">{{$key+1}}</td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-6">

                                              <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Makanan Pagi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($item['data']['pagi'] as $dataKey => $childItem)
                                                    <tr>
                                                        <td>{{$dataKey}}</td>
                                                       <td>{{$childItem['makanan']}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                              <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Makanan Siang</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($item['data']['siang'] as $dataKey => $childItem)
                                                    <tr>
                                                        <td>{{$dataKey}}</td>
                                                        <td>{{$childItem['makanan']}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                              <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Makanan Malam</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($item['data']['malam'] as $dataKey => $childItem)
                                                    <tr>
                                                        <td>{{$dataKey}}</td>
                                                        <td>{{$childItem['makanan']}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @php $total = [];
                                             $total['kalori'] = 0;
                                             $total['karbohidrat'] = 0;
                                             $total['protein'] = 0;
                                             $total['lemak'] = 0;
                                             $total['berat'] = 0;
                                        @endphp
                                        <div class="col-md-6">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Kalori</th>
                                                        <th>Berat</th>
                                                        <th>Kabohidrat</th>
                                                        <th>Protein</th>
                                                        <th>Lemak</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($item['data']['pagi'] as $dataKey => $childItem)
                                                    <tr>
                                                        <td>{{$childItem['kalori']}}</td>
                                                        <td>{{$childItem['berat']}}</td>
                                                        <td>{{$childItem['karbohidrat']}}</td>
                                                        <td>{{$childItem['protein']}}</td>
                                                        <td>{{$childItem['lemak']}}</td>
                                                    </tr>

                                                    @php
                                                         $total['kalori'] += $childItem['kalori'];
                                                         $total['karbohidrat'] += $childItem['karbohidrat'];
                                                         $total['protein'] += $childItem['protein'];
                                                         $total['lemak'] += $childItem['lemak'];
                                                         $total['berat'] += $childItem['berat'];
                                                    @endphp
                                                    @endforeach
                                                </tbody>
                                            </table>

                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Kalori</th>
                                                        <th>Berat</th>
                                                        <th>Kabohidrat</th>
                                                        <th>Protein</th>
                                                        <th>Lemak</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($item['data']['siang'] as $dataKey => $childItem)
                                                    <tr>
                                                        <td>{{$childItem['kalori']}}</td>
                                                        <td>{{$childItem['berat']}}</td>
                                                        <td>{{$childItem['karbohidrat']}}</td>
                                                        <td>{{$childItem['protein']}}</td>
                                                        <td>{{$childItem['lemak']}}</td>
                                                    </tr>

                                                    @php
                                                         $total['kalori'] += $childItem['kalori'];
                                                         $total['karbohidrat'] += $childItem['karbohidrat'];
                                                         $total['protein'] += $childItem['protein'];
                                                         $total['lemak'] += $childItem['lemak'];
                                                         $total['berat'] += $childItem['berat'];
                                                    @endphp
                                                    @endforeach
                                                </tbody>
                                            </table>

                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Kalori</th>
                                                        <th>Berat</th>
                                                        <th>Kabohidrat</th>
                                                        <th>Protein</th>
                                                        <th>Lemak</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($item['data']['malam'] as $dataKey => $childItem)
                                                    <tr>
                                                        <td>{{$childItem['kalori']}}</td>
                                                        <td>{{$childItem['berat']}}</td>
                                                        <td >{{$childItem['karbohidrat']}}</td>
                                                        <td>{{$childItem['protein']}}</td>
                                                        <td>{{$childItem['lemak']}}</td>
                                                    </tr>

                                                    @php
                                                         $total['kalori'] += $childItem['kalori'];
                                                         $total['karbohidrat'] += $childItem['karbohidrat'];
                                                         $total['protein'] += $childItem['protein'];
                                                         $total['lemak'] += $childItem['lemak'];
                                                         $total['berat'] += $childItem['berat'];
                                                    @endphp
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th style="text-align:center;" colspan="4">Total</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: ">{{$total['kalori']}}</th>
                                                        <th style="text-align: ">{{$total['berat']}}</th>
                                                        <th style="text-align: ">{{$total['karbohidrat']}}</th>
                                                        <th style="text-align: ">{{$total['protein']}}</th>
                                                        <th style="text-align: ">{{$total['lemak']}}</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="col-md-6" style="display: none;">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Kalori</th>
                                                        <th>Berat</th>
                                                        <th>Kabohidrat</th>
                                                        <th>Protein</th>
                                                        <th>Lemak</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($item['data']['malam'] as $dataKey => $childItem)
                                                    <tr>
                                                        <td>{{$childItem['kalori']}}</td>
                                                        <td>{{$childItem['berat']}}</td>
                                                        <td>{{$childItem['karbohidrat']}}</td>
                                                        <td>{{$childItem['protein']}}</td>
                                                        <td>{{$childItem['lemak']}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                       
                                                    </tr>
                                                </thead>
                                               
                                            </table>
                                        </div>
                                    </div>
                                    
                                </td>
        
                            </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
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
