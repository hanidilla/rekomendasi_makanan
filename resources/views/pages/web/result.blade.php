@extends('pages.web.layouts.main')


@section('content')


<section class="about_section" id="about">
    <div class="container">
        <div class="align-items-center mb-4" style="padding-top: 3%;">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="h3 mb-0 text-gray-800">Saran Makanan</h1>
                </div>
                <div class="col-md-6">
                    <form action="{{url('/')}}">
                        <div class="input-group">
                            <input type="text" class="form-control " placeholder="Cari Kode Pasien Atau Kode Kebutuhan Pasien" aria-label="Search" required name="kode_pasien" aria-describedby="basic-addon2" value="{{$request->kode_pasien}}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="card z-depth-0 bordered">
            <div class="card-header">
                <p align="center">
                    Hasil Yang Ditemukan : {{count($dataRet)}}
                </p>
            </div>
        </div>
        <div class="accordion" id="accordionExample">
            @foreach($dataRet as $key => $item)
            <div class="card border-0 shadow">
                <div class="card-header">
                    <h5 class="mb-0">
                        Data ke - {{$key+1}}
                        <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#collapseOne{{$key+1}}" aria-expanded="true" aria-controls="collapseOne{{$key+1}}">
                            Lihat Data <i class="fa fa-eye"></i>
                        </button>
                    </h5>
                </div>
            </div>
            <div id="collapseOne{{$key+1}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">

                    <div class="col-md-12" align="center">
                        <ul>
                            <u>
                                <h4>Pasien</h4>
                            </u>
                            <li style="list-style-type: none">
                                <b>Nama</b> : {{$item['nama_pasien']}}
                            </li>
                            <li style="list-style-type: none">
                                <b>Umur</b> : {{$item['umur']}}
                            </li>
                            <li style="list-style-type: none">
                                <b>Tinggi</b> : {{$item['tinggi']}}
                            </li>
                            <li style="list-style-type: none">
                                <b>Berat</b> : {{$item['berat']}}
                            </li>
                            <li style="list-style-type: none">
                                <b>Jenis Kelamin</b> : {{$item['jenis_kelamin']}}
                            </li>
                        </ul>
                    </div>


                    <div class="table-responsive">

                        <div class="row">
                            <div class="col-md-4">
                                <ul>
                                    <b>Makanan Pagi</b>
                                    @foreach($item['data']['pagi'] as $dataKey => $childItem)
                                    <li>{{$dataKey+1}} - {{$childItem['makanan']}}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <ul>
                                    <b>Makanan Pagi</b>
                                    @foreach($item['data']['siang'] as $dataKey => $childItem)
                                    <li>{{$dataKey+1}} - {{$childItem['makanan']}}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <ul>
                                    <b>Makanan Pagi</b>
                                    @foreach($item['data']['malam'] as $dataKey => $childItem)
                                    <li>{{$dataKey+1}} - {{$childItem['makanan']}}</li>
                                    @endforeach
                                </ul>
                            </div>

                        </div>


                    </div>

                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>






@endsection