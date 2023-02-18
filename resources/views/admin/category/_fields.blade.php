<div class="row">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4">
        <div class="form-group">
            <label for="id_title">Title</label>
            <input id="id_title" type="text" class="form-control @if ($errors->has('title')) is-invalid @endif"
                   name="title" value="{{ old('title', !empty($model) ? $model->title : null) }}" required autofocus>
            @if ($errors->has('title'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('title') }}</strong>
                </div>
            @endif
        </div>
    </div>
</div>

@if(!empty($model))
    <div class="row">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4">
            <div class="form-group">
                <label for="id_alias">Alias (Generated automatically)</label>
                <input id="id_alias" type="text" class="form-control" value="{{ $model->alias }}" disabled>
            </div>
        </div>
    </div>
@endif