<div class="pb-2 row">
  <div class="col-md-5 col-12">
    <input class="form-control" name="add_social_account_name[]" placeholder="@lang('label.account.social_placeholder')"/>
  </div>
  <div class="col-md-5 col-12">
    <select class="form-control" name="add_social_service_name[]">
    @foreach($apps as $value)
      <option value="{{ $value->id }}">{{ ucfirst($value->name) }}</option>
    @endforeach
    </select>
  </div>
  <div class="col-md-1 col-12">
    <button type="button" class="btn remove_app" role="button">
      <i class="fa fa-minus-circle"></i>
    </button>
  </div>
</div>
