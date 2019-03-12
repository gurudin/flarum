@extends('layouts.admin')

@section('title')
Category
@endsection

@section('css')
  
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title">
      Menu <small class="text-muted">create & update</small>
      <a :href="href.index" class="btn btn-light float-right" v-if="init.m.id!=''">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </h4>
    <hr>

    <form>
      <div class="form-group col-4">
        <label>Parent category</label>
        <select class="form-control" v-model.number="init.m.parent_id">
          <option value="0">顶级类别</option>
        </select>
      </div>

      <div class="form-group col-8">
        <label>Category <small>*</small></label>
        <input type="text" class="form-control" v-model.trim="init.m.category" placeholder="Enter category name">
      </div>

      <div class="form-group col-8">
        <label>Alias <small>*</small></label>
        <input type="text" class="form-control" v-model.trim="init.m.alias" placeholder="Enter category alias">
      </div>

      <div class="form-group col-5">
        <label>Color <small>*</small></label>
        <div class="input-group mb-3">
          <input type="text" class="form-control" v-model.trim="init.m.color" placeholder="Enter category alias">
          <div class="input-group-append">
            <span class="input-group-text">
              <input type="color" v-model.trim="init.m.color">
            </span>
          </div>
        </div>
      </div>

      <div class="form-group col-8">
        <label>Weight</label>
        <input type="number" class="form-control" v-model.trim="init.m.weight" placeholder="Enter weight">
      </div>

      <div class="form-group col-8">
        <label>Icon</label>
        <vue-upload-picker
          v-model="init.m.pic"
          :post-uri="href.upload"
          title="Upload category icon"
          icon='<i class="fas fa-file-import"></i>'
          class-name="btn btn-primary btn-sm text-white"></vue-upload-picker>
      </div>

      <div class="form-group col-8">
        <label>Remark</label>
        <textarea class="form-control" rows="3" v-model.trim="init.m.remark" placeholder="Enter remark"></textarea>
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
<script>
new Vue({
  el: "#app",
  data() {
    return {
      init: {
        m: @json($m),
      },
      href: {
        index: "{{route('admin.category.list')}}",
        current: "{{route('admin.category.save')}}",
        upload: "{{route('admin.upload')}}",
      },
    };
  },
  computed: {
    isValid() {
      var m = this.init.m;
      if (m.category == ''
        || m.alias == ''
        || m.color == ''
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
      axios.post(this.href.current, {
        action: 'save',
        data: this.init.m
      }).then(function (response) {
        if (response.status) {
          window.location.href = _this.href.index;
        } else {
          alert(response.msg);
          $btn.loading('reset');
        }
      });
    },
  }
});
</script>
@endsection
