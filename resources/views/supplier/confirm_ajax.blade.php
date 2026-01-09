@empty($supplier)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">Data tidak ditemukan</div>
            </div>
        </div>
    </div>
@else
<form action="{{ url('/supplier/' . $supplier->supplier_id . '/delete_ajax') }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Data Supplier</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-ban"></i> Konfirmasi!</h5>
                    Apakah Anda yakin ingin menghapus data berikut?
                    <table class="table table-sm table-bordered mt-2">
                        <tr><th>Kode Supplier</th><td>{{ $supplier->supplier_kode }}</td></tr>
                        <tr><th>Nama Supplier</th><td>{{ $supplier->supplier_nama }}</td></tr>
                        <tr><th>Alamat</th><td>{{ $supplier->supplier_alamat }}</td></tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-delete").validate({
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide'); // Menutup modal
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            // Reload tabel secara otomatis
                            if (typeof tableSupplier !== 'undefined') {
                                tableSupplier.ajax.reload();
                            } else {
                                // Jika variabel global gagal, gunakan ID tabel
                                $('#table_supplier').DataTable().ajax.reload();
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Gagal menghapus data. Pastikan tidak ada data yang terkait.'
                        });
                    }
                });
                return false;
            }
        });
    });
</script>
@endempty