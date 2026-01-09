<form action="{{ url('/penjualan/ajax') }}" method="POST" id="form-tambah-penjualan">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Transaksi Penjualan (Ajax)</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kasir</label>
                    <select name="user_id" class="form-control" required>
                        @foreach($user as $u) <option value="{{ $u->user_id }}">{{ $u->nama }}</option> @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Kode Transaksi</label>
                    <input type="text" name="penjualan_kode" class="form-control" value="PJN{{ time() }}" readonly>
                </div>
                <div class="form-group">
                    <label>Pembeli</label>
                    <input type="text" name="pembeli" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="datetime-local" name="penjualan_tanggal" class="form-control" required>
                </div>
                <hr>
                <h6>Detail Barang</h6>
                <div class="row">
                    <div class="col-8">
                        <select id="item_barang" class="form-control">
                            <option value="">- Pilih Barang -</option>
                            @foreach($barang as $b) <option value="{{ $b->barang_id }}">{{ $b->barang_nama }} (Rp{{ $b->harga_jual }})</option> @endforeach
                        </select>
                    </div>
                    <div class="col-2"><input type="number" id="item_jumlah" class="form-control" placeholder="Qty"></div>
                    <div class="col-2"><button type="button" class="btn btn-success btn-block" id="add-item">Tambah</button></div>
                </div>
                <table class="table table-sm mt-3" id="table-detail">
                    <thead><tr><th>Nama Barang</th><th>Jumlah</th><th>Aksi</th></tr></thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#add-item').click(function() {
            let id = $('#item_barang').val();
            let nama = $('#item_barang option:selected').text();
            let qty = $('#item_jumlah').val();
            if(id && qty > 0) {
                $('#table-detail tbody').append(`
                    <tr>
                        <td><input type="hidden" name="barang_id[]" value="${id}">${nama}</td>
                        <td><input type="hidden" name="jumlah[]" value="${qty}">${qty}</td>
                        <td><button type="button" class="btn btn-danger btn-xs remove-item">X</button></td>
                    </tr>
                `);
            }
        });

        $(document).on('click', '.remove-item', function() { $(this).closest('tr').remove(); });

        $("#form-tambah-penjualan").validate({
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if(response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', response.message, 'success');
                            tablePenjualan.ajax.reload();
                        } else {
                            Swal.fire('Gagal', response.message, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });
</script>