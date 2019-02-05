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
App.follow = function(element,data,cache){
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
App.loadData = function(a,f){
  App.query('get',f+1,null,false,function(data){
    $(a).append(data.posts);
  });
}
App.upload = function(n, resize = true){
  var node = setNode(n, {
    html:
    {
      id : 'node_'+n.id
    }
  },"div");

  $("<div/>").html("<div class='preview_body'><div class='image-wrapper' id='preview-wrapper'><img id='image_"+node.html.id+"' src="+URL.createObjectURL(event.target.files[0])+"></div></div>").appendTo($("#"+node.html.id));

  if(resize){
    $('#image_'+node.html.id).rcrop();
  }
}

function setNode(e, params, element){
  var parent = document.getElementById(e.id);
  var child = document.createElement(element);
  var a = new Array();
  for(var i in params){
    if(typeof params[i] == "object"){
      a[i] = new Array(Object.keys(params[i]).length);
      for(var p in params[i]){
        a[i][p] = params[i][p];
        child.setAttribute([p], a[i][p]);
      }
    }else{
      a[i] = params[i];
      if(i == "text"){
        var text = document.createTextNode(a[i]);
        child.appendChild(text);
      }
    }
  }
  parent.appendChild(child);

  return a;
}
