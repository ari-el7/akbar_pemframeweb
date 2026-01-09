@empty($penjualan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">Data transaksi tidak ditemukan</div>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax') }}" method="POST" id="form-delete-penjualan">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Transaksi Penjualan</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Peringatan!</h5>
                        Data transaksi <strong>{{ $penjualan->penjualan_kode }}</strong> akan dihapus permanen beserta seluruh detail barangnya. Apakah Anda yakin?
                    </div>
                    <table class="table table-sm table-bordered">
                        <tr><th>Kode</th><td>{{ $penjualan->penjualan_kode }}</td></tr>
                        <tr><th>Pembeli</th><td>{{ $penjualan->pembeli }}</td></tr>
                        <tr><th>Tanggal</th><td>{{ $penjualan->penjualan_tanggal }}</td></tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Transaksi</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            $("#form-delete-penjualan").validate({
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
                                tablePenjualan.ajax.reload();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                }
            });
        });
    </script>
@endempty