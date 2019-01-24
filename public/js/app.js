var App = {};
App.query = function(method,url,data,cache,f = null){
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
App.loadData = function(a,f,m,t = null){
  //for(var i in t.callback)
    //App.call.(t.callback[i]);
  App.query('get',f+1,null,false,function(data){
    $(a).append(data.posts);
  });
}
App.upIm = function(data){
  for(var i in data.settings){
    $("#"+data.settings[i]).change(function(){
      //$("<div/>").html("<div class='preview_body'><div class='image-wrapper' id='preview-wrapper'><img id='image' src="+URL.createObjectURL(event.target.files[0])+"></div></div>").appendTo($("#avatar_preview"));
      //$('#image').rcrop();
    });
  }
}
