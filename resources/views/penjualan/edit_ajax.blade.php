@empty($penjualan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger"> Data transaksi tidak ditemukan</div>
            </div>
        </div>
    </div>
@else
<form action="{{ url('/penjualan/' . $penjualan->penjualan_id . '/update_ajax') }}" method="POST" id="form-edit-penjualan">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Transaksi Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kasir</label>
                            <select name="user_id" class="form-control" required>
                                @foreach($user as $u)
                                    <option value="{{ $u->user_id }}" {{ $u->user_id == $penjualan->user_id ? 'selected' : '' }}>{{ $u->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kode Transaksi</label>
                            <input type="text" class="form-control" value="{{ $penjualan->penjualan_kode }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Pembeli</label>
                    <input type="text" name="pembeli" class="form-control" value="{{ $penjualan->pembeli }}" required>
                </div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="datetime-local" name="penjualan_tanggal" class="form-control" 
                           value="{{ date('Y-m-d\TH:i', strtotime($penjualan->penjualan_tanggal)) }}" required>
                </div>

                <hr>
                <h6>Detail Item Barang</h6>
                <table class="table table-sm table-bordered" id="table-edit-detail">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th width="150">Harga Satuan</th>
                            <th width="100">Jumlah</th>
                            <th width="150">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjualan->detail as $item)
                        <tr>
                            <td>
                                <input type="hidden" name="detail_id[]" value="{{ $item->detail_id }}">
                                <input type="hidden" name="barang_id[]" value="{{ $item->barang_id }}">
                                {{ $item->barang->barang_nama }}
                            </td>
                            <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>
                                <input type="number" name="jumlah[]" class="form-control form-control-sm input-jumlah" 
                                       value="{{ $item->jumlah }}" min="1" required>
                            </td>
                            <td class="subtotal">Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-edit-penjualan").validate({
            rules: {
                user_id: {required: true, number: true},
                pembeli: {required: true, minlength: 3},
                penjualan_tanggal: {required: true},
                "jumlah[]": {required: true, number: true, min: 1}
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            tablePenjualan.ajax.reload(); // Memanggil instance table dari index
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element) { $(element).addClass('is-invalid'); },
            unhighlight: function (element) { $(element).removeClass('is-invalid'); }
        });
    });
</script>
@endempty