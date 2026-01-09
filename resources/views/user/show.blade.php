@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        @empty($user)
            <div class="alert alert-danger alert-dismissible">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                Data yang Anda cari tidak ditemukan.
            </div>
        @else
            <table class="table table-bordered table-striped table-hover table-sm">
                <tr>
                    <th>Avatar</th>
                    <td>
                        {{-- Mengakses file dari folder storage/photos/ --}}
                         @if($user->avatar)
                                <img src="{{ asset( $user->avatar) }}" class="img-thumbnail" style="width: 150px;">
                            @else
                                <img src="{{ asset('adminlte/dist/img/user2-160x160.jpg') }}" class="img-thumbnail" style="width: 150px;">
                            @endif
                    </td>
                </tr>
                <tr>
                    <th width="20%">ID</th>
                    <td>{{ $user->user_id }}</td>
                </tr>
                <tr>
                    <th>Level</th>
                    <td>{{ $user->level->level_nama }}</td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td>{{ $user->username }}</td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>{{ $user->nama }}</td>
                </tr>
                <tr>
                    <th>Password</th>
                    {{-- Menampilkan hash password dari database --}}
                    <td style="word-break: break-all;">{{ $user->password }}</td>
                </tr>
            </table>
        @endempty
        <a href="{{ url('user') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
    </div>
</div>
@endsection