@extends('layouts.app')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ahli Gizi</h1>
        <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#myModal"><i
                class="fas fa-plus fa-sm text-white-50"></i> Tambah Data</button>
    </div>
    @if($message=Session::get('success'))
        <div class="alert alert-success" role="alert">
            <div class="alert-text">{{ucwords($message)}}</div>
        </div>
    @endif
    @if($message=Session::get('error'))
        <div class="alert alert-danger" role="alert">
            <div class="alert-text">{{ucwords($message)}}</div>
        </div>
    @endif
    <div class="card border-0 shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $key => $item)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->email}}</td>
                                <td>
                                    <a style="cursor: pointer;" data-toggle="modal" data-target="#myModalEdit{{$item->id}}" 
                                       class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    &nbsp;
                                    <a href="{{url('ahli-gizi-delete/'.$item->id)}}" 
                                       onclick="return confirm('Yakin untuk menghapus')" 
                                       class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <div class="modal" id="myModalEdit{{$item->id}}">
                                <div class="modal-dialog">
                                    <form action="{{url('ahli-gizi-update/'.$item->id)}}" method="POST">
                                        @csrf
                                    <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="">Nama </label>
                                            <input type="text" placeholder="Dilla Hani" required name="name" class="form-control form-control-sm" value="{{$item->name}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Email</label>
                                            <input type="email" name="email" placeholder="dilla@gmail.com" required class="form-control form-control-sm" value="{{$item->email}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Password</label>
                                            <input type="password" name="password" placeholder="*******"  class="form-control form-control-sm" id="berat">
                                            <small>Kosongi saja apabila tidak ingin di ganti</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-sm btn-secondary close-modal" data-dismiss="modal">Tutup</button>&nbsp;
                                                <button class="btn btn-sm btn-primary" type="submit" id="btn-save-bahan">Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<div class="modal" id="myModal">
    <div class="modal-dialog">
        <form action="{{url('ahli-gizi-store')}}" method="POST">
            @csrf
        <div class="modal-content">
        <div class="modal-body">
            <div class="form-group">
                <label for="">Nama </label>
                <input type="text" placeholder="Dilla Hani" required name="name" class="form-control form-control-sm" id="nama_bahan">
            </div>
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" placeholder="dilla@gmail.com" required class="form-control form-control-sm" id="berat">
            </div>
            <div class="form-group">
                <label for="">Password</label>
                <input type="password" name="password" placeholder="*******" required class="form-control form-control-sm" id="berat">
            </div>
        </div>
        <div class="modal-footer">
                <div class="d-flex justify-content-end">
                    <button class="btn btn-sm btn-secondary close-modal" data-dismiss="modal">Tutup</button>&nbsp;
                    <button class="btn btn-sm btn-primary" type="submit" id="btn-save-bahan">Simpan</button>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>



@endsection


@section('script')
<script type="text/javascript">
$(function () {
    $("#example1").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
  });
</script>
@endsection
