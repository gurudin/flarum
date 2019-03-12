@extends('layouts.admin')

@section('title') Posts @endsection

@section('css')
  
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title">Posts <small class="text-muted">create & update</small>
      <a :href="init.href.list" class="btn btn-light float-right">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </h4>
    <hr>

    <form>
      <div class="form-group col-8">
        <label>Category *</label>
        <select class="form-control" v-model="init.m.fk_category_id">
          <option value="">--Select one--</option>
          <option v-for="(id,name) in init.category" :value="id">@{{name}}</option>
        </select>
      </div>

      <div class="form-group col-8">
        <label>Reading level *</label>
        <select class="form-control" v-model="init.m.read_level">
          <option v-for="(remark,level) in init.levels" :value="level">@{{remark}}</option>
        </select>
      </div>

      <div class="form-group col-8">
        <label>Article title *</label>
        <input type="text" class="form-control" v-model.trim="init.m.title" placeholder="Enter article title">
      </div>

      <div class="form-group col-8">
        <label>Coins *</label>
        <input type="number" class="form-control" v-model.number="init.m.coin" placeholder="Enter weight">
      </div>

      <div class="form-group col-8">
        <label>Weight *</label>
        <input type="number" class="form-control" v-model.number="init.m.weight" placeholder="Enter weight">
      </div>

      <div class="form-group col-8">
        <label>Cover *</label>
        <vue-upload-picker
          v-model="init.m.cover"
          :post-uri="init.href.upload"
          title="Upload category icon"
          icon='<i class="fas fa-file-import"></i>'
          class-name="btn btn-primary btn-sm text-white"></vue-upload-picker>
      </div>

      <div class="form-group col-8">
        <label>Enter tags</label>
        <input type="text" class="form-control" v-model.number="init.m.tags" placeholder="Enter tags">
      </div>

      <div class="form-group col-8">
        <label>Remark</label>
        <textarea class="form-control" rows="3" v-model.trim="init.m.remark" placeholder="Enter remark"></textarea>
      </div>

      <div class="form-group col-10">
        <label>Content *</label>
        <textarea id="content"></textarea>
      </div>

      <div class="form-group col-4">
        <button type="button" class="btn btn-success" :disabled="!isValid" @click="save">
          <i class="fas fa-paper-plane"></i> Save
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/vue-upload-picker.js') }}"></script>
<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
<script>
const vm = new Vue({
  el: "#app",
  data() {
    return {
      init: {
        m: @json($m),
        category: @json($category),
        levels: @json($levels),
        href: {
          list: "{{route('admin.posts.list')}}",
          upload: "{{route('admin.upload')}}",
          current: "{{route('admin.posts.save')}}",
        },
      },
    };
  },
  computed: {
    isValid() {
      var m = this.init.m;
      if (m.fk_category_id == ''
        || m.title == ''
        || m.content == ''
        || m.coin === ''
        || m.weight === ''
        || m.cover == ''
        || m.content == ''
      ) {
        return false;
      }

      return true;
    }
  },
  methods: {
    save(event) {
      var $btn = $(event.currentTarget).loading('<i class="fas fa-spinner fa-spin"></i>');

      var _this = this;
      axios.post(this.init.href.current, {
        action: 'save',
        data: this.init.m
      }).then(function (response) {
        if (response.data.status) {
          window.location.href = _this.init.href.list;
        } else {
          alert(response.data.msg);
          $btn.loading('reset');
        }
      });
    },
  }
});

tinymce.init({
  selector: '#content',
  // language: 'zh_CN',
  browser_spellcheck: true,
  contextmenu: false,
  height: 300,
  plugins: [
    "advlist autolink lists link image charmap print preview anchor",
    "searchreplace visualblocks  fullscreen codesample",
    "insertdatetime media table contextmenu paste imagetools wordcount",
  ],
  toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | codesample | preview | fullscreen",
  convert_urls: false,
  images_upload_handler: function (blobInfo, success, failure) {
    var data = new FormData();
    data.append('file', blobInfo.blob());
    $.ajax({
      url: vm.init.href.upload,
      type: 'POST',
      cache: false,
      data: data,
      processData: false,
      contentType: false,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
    }).done(function (response) {
      if (response.status) {
        success(response.path);
      } else {
        failure(response.msg);
      }
    }).fail(function (res) {
      console.log(res);
      failure('Upload error.')
    });
  },
  init_instance_callback: function (editor) {
    tinymce.activeEditor.setContent(vm.init.m.content);
    
    editor.on('KeyUp', function (e) {
      vm.init.m.content = tinymce.activeEditor.getContent();
    });

    editor.on('Change', function (e) {
      vm.init.m.content = tinymce.activeEditor.getContent();
    });
  }
});
</script>
@endsection
