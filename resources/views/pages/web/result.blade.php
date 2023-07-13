@extends('pages.web.layouts.main')


@section('content')


  <section class="about_section" id="about">
    <div class="container">
      <div class="row" style="padding-top: 5%">
        @foreach($dataRet as $key => $item)
            <div class="col-md-6">
                <ul>
                    <u><h4>Pasien</h4></u>
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
            </div>
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
                                                    <th>Kabohidrat</th>
                                                    <th>Protein</th>
                                                    <th>Lemak</th>
                                                    <th>Berat</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($item['data']['pagi'] as $dataKey => $childItem)
                                                <tr>
                                                    <td>{{$childItem['kalori']}}</td>
                                                    <td>{{$childItem['karbohidrat']}}</td>
                                                    <td>{{$childItem['protein']}}</td>
                                                    <td>{{$childItem['lemak']}}</td>
                                                    <td>{{$childItem['berat']}}</td>
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
                                                    <th>Kabohidrat</th>
                                                    <th>Protein</th>
                                                    <th>Lemak</th>
                                                    <th>Berat</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($item['data']['siang'] as $dataKey => $childItem)
                                                <tr>
                                                    <td>{{$childItem['kalori']}}</td>
                                                    <td>{{$childItem['karbohidrat']}}</td>
                                                    <td>{{$childItem['protein']}}</td>
                                                    <td>{{$childItem['lemak']}}</td>
                                                    <td>{{$childItem['berat']}}</td>
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
                                                    <th>Kabohidrat</th>
                                                    <th>Protein</th>
                                                    <th>Lemak</th>
                                                    <th>Berat</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($item['data']['malam'] as $dataKey => $childItem)
                                                <tr>
                                                    <td>{{$childItem['kalori']}}</td>
                                                    <td >{{$childItem['karbohidrat']}}</td>
                                                    <td>{{$childItem['protein']}}</td>
                                                    <td>{{$childItem['lemak']}}</td>
                                                    <td>{{$childItem['berat']}}</td>
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
                                                    <th style="text-align: ">{{$total['karbohidrat']}}</th>
                                                    <th style="text-align: ">{{$total['protein']}}</th>
                                                    <th style="text-align: ">{{$total['lemak']}}</th>
                                                    <th style="text-align: ">{{$total['berat']}}</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="col-md-6" style="opacity: 0">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Kalori</th>
                                                    <th>Kabohidrat</th>
                                                    <th>Protein</th>
                                                    <th>Lemak</th>
                                                    <th>Berat</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($item['data']['malam'] as $dataKey => $childItem)
                                                <tr>
                                                    <td>{{$childItem['kalori']}}</td>
                                                    <td>{{$childItem['karbohidrat']}}</td>
                                                    <td>{{$childItem['protein']}}</td>
                                                    <td>{{$childItem['lemak']}}</td>
                                                    <td>{{$childItem['berat']}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <!-- <th style="text-align: center">Total :</th> -->
                                                   
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
        @endforeach
    </div>
    </div>
  </section>






  @endsection