<style>
#nav > li {
  display: inline-block;
  margin-top: 5px;
  margin-bottom: 10px;
  font-size: 20px;
}
#nav > li:not(:last-child)::after {
  content: '\00a0|';
}
._ou {
  cursor: pointer;
}
</style>
  <div class="publisher-home">
    <div class="publisher-header" style="background-image: url({{ $query->getBackground() }});border-radius:4px 4px 0 0;">
      <div class="container">
        <div class="publisher-logo">
          <div class="row">
            <div class="d-inline">
              <img src="{{ $query->getAvatar() }}" alt="Logo">
            </div>
            <div class="col-lg-10 col-sm-col-xs-12">
              <div class="mt-2 info">
                <span>{{ $query->name }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <nav class="publisher-nav">
      <ul id='nav'>
        <li><a href="{{ url('groups/'. $query->slug) }}">Bacheca</a></li>
        <li><a href="{{ url('groups/'. $query->slug.'/about') }}">Informazioni</a></li>
      </ul>
    </nav>
    <hr/>
    <div class="publisher-body">
      <div class="publisher-info">
