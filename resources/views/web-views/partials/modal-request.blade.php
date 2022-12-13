<div class="modal fade" id="modal-request" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Form Permintaan Produk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('request-product') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label for="exampleInputEmail1">Nama Produk</label>
            <input type="text" class="form-control" name="nama" placeholder="Nama produk" required>
          </div>
          <div class="form-group">
            <label>Jumlah</label>
            <input type="number" class="form-control" name="jumlah" required>
          </div>
          <div class="form-group">
            <label>Nomor Whatsapp</label>
            <input type="number" class="form-control" name="phone" placeholder="0822 2222 2222" required>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Link Referensi</label>
            <input type="text" class="form-control" name="link" placeholder="https://tokopedia/set-topbox.com" required>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Deskripsi produk</label>
            <textarea name="deskripsi" cols="30" rows="3" class="form-control" required></textarea>
          </div>
          <div class="form-group">
            <div class="d-block">
              <label class="d-block">Foto produk</label>
              <img id="blah"
                   style="width: 123px!important;height: 136px!important;"
                   class="border"
                   onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                   src="{{asset('public/assets/front-end/img/image-place-holder.png')}}">

              <div class="col-md-10">
                  <label for="files"
                         style="cursor: pointer; color:{{$web_config['primary_color']}};"
                         class="spandHeadO">
                      {{\App\CPU\translate('Pilih foto produk')}}
                  </label>
                  <input id="files" name="image" style="visibility:hidden;" type="file">
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Ajukan Produk</button>
      </div>
      </form>
    </div>
  </div>
</div>
@push('script')
<script>
  function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $("#files").change(function () {
            readURL(this);
        });
</script>

@endpush
