<div class="row">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4">
        @include('mediafiles.thumbnail', ['model' => $model])
    </div>
</div>
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

<div class="row">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4">
        <div class="form-group">
            <label for="id_description">Description</label>
            <textarea id="id_description" type="text" class="form-control @if ($errors->has('description')) is-invalid @endif"
            name="description" required autofocus>{{ old('description', !empty($model) ? $model->description : null) }}</textarea>
            @if ($errors->has('description'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('description') }}</strong>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6 col-sm-6 col-md-6 col-lg-4 col-xl-2">
        <div class="form-group">
            <label for="id_price">Price</label>
            <input id="id_price" type="text" class="form-control @if ($errors->has('price')) is-invalid @endif"
                   name="price" value="{{ old('price', !empty($model) ? $model->price : null) }}" required autofocus>
            @if ($errors->has('price'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('price') }}</strong>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4">
        <div class="form-group mb-3">
            <label for="id_category">Category</label>
            <select name="category_id" class="custom-select @if ($errors->has('category_id')) is-invalid @endif" id="id_category" aria-describedby="validationCatIdFeedback">
                <option value="0" selected disabled>Choose...</option>
                @foreach($categories as $catId => $catTitle)
                    <option value="{{ $catId }}" @if(!empty($model) && $catId == $model->category->id) selected @endif >{{ $catTitle }}</option>
                @endforeach
            </select>
            @if ($errors->has('category_id'))
                <div class="invalid-feedback" id="validationCatIdFeedback">
                    <strong>{{ $errors->first('category_id') }}</strong>
                </div>
            @endif
        </div>
    </div>
</div>
