<div class="form-group row">
  <input type="hidden" name="datetime" value="{{ $date }} {{ $time}}" />
  <label for="schedule" class="col-md-4 col-form-label"><span class="fa fa-clock"></span> @lang('Programma pubblicazione')</label>
    <div class="col-md-12 row">
      <div class="col-6">
        <input type="date" class="form-control" value="{{ $date }}" disabled />
      </div>
      <div class="col-6">
        <input type="time" class="form-control" value="{{ $time }}" disabled />
      </div>
    </div>
</div>
