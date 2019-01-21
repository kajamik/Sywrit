var App = {} || [];
App.query = function(method,url,data,cache,f){
    $.ajax({
      method: method,
      url: url,
      data: data,
      cache: cache,
      success: f
    });
}
App.follow = function(element,data,cache,f = null){
  $(element).click(function(){
    var attribute = $(".publisher-bar").attr("data-pub-text");
    App.query('get',data.url,data.data,cache,
      function(data){
        if(data.result){
          $("#follow i").attr("class","fas fa-bell-slash");
          $("#follow span").html("Smetti di seguire");
        }else{
          $("#follow i").attr("class","fas fa-bell");
          $("#follow span").html("Inizia a seguire");
        }
        $(attribute).html( data.counter );
      });
  });
}
App.insl = function(id){
  var page = 1;
  document.addEventListener('scroll', function (event) {
    if ($(window).scrollTop() == $(document).height() - $(window).height()) {
      page++;
      App.query('get','?page='+(page),null,false,function(data){
        $("#"+id).append(data.posts);
      });
    }
  });
}
