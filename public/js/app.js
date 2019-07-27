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
  document.addEventListener('scroll', function () {
    if ($(window).scrollTop() == $(document).height() - $(window).height()) {
      page++;
      App.query('get','?page='+(page),null,false,function(data){
        $("#"+id).append(data.posts);
      });
    }
  });
}

App.loadData = function(a, f){
  App.query('get',f+1,null,false,function(data){
    $(a).append(data.posts);
  });
}

App.upload = function(b){
  $(b).html("<div class='preview_body'><div class='image-wrapper' id='preview-wrapper'><img id='image_"+$(b).attr('id')+"' src="+URL.createObjectURL(event.target.files[0])+"></div></div>");
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

function search_item(s, v){
  for(var i in s){
    if(i == v)
      return s[i];
  }
  return null;
}

App.getUserInterface = function(t, e){

  $(".__ui__g").remove();

  $("body").css('overflow','hidden');

  for(var i in t){
    var header = search_item(t[i], 'header');
    var data = search_item(t[i], 'data');
    var title = search_item(t[i], 'title');
    var content = search_item(t[i], 'content');
    var done = search_item(t[i], "done");
    // styles
    var style = search_item(t[i], "styles");
  }

  $("<div class='__ui__g'><div class='__ui__g_container'><div class='__ui__g_header'><div class='__ui__g_title'>"+title+"</div><div class='__ui__g_close'>&times;</div></div><div class='__ui__g_body'></div></div></div>").appendTo( $("body") );

  if(header != null){
    $("<form action='"+header.action+"' method='"+header.method+"'><div class='__ui__g_form_control'></div></form>").appendTo( $(".__ui__g_body") );
  }

 var f = function(b){
   var str;
   var className = (b.class) ? "class='"+b.class+"'" : '';

    var id = (b.id) ? "id="+(b.id) : '';
    var name = (b.name) ? "name="+(b.name) : '';
    var value = (b.value) ? "value="+(b.value) : '';
    var type = (b.type.length > 1) ? "type="+(b.type[1]) : '';
    var target = (b.target) ? "target="+(b.target) : '';
    var href = (b.href) ? "href="+(b.href) : '';
    var placeholder = (b.placeholder) ? "placeholder='"+b.placeholder+"'" : '';
    var required = (b.required) ? 'required' : '';
    var text = (b.text) ? b.text : '';
    str = '<'+b.type[0]+' '+id+' '+href+' '+className+' '+type+' '+value+' '+name+' '+placeholder+' '+required+' '+target+'>'+text+'</'+b.type[0]+'>';

  return str;
 }

  var n = 0;
  if(typeof content == 'string') {
    $(".__ui__g_body").append(content);
  } else {
    for(var i in content){
      if(typeof content[i].type[0] == 'object') {
        var select = content[i].type[0].select;
        var selClass = $("<div class='form-group'><select id='"+content[i].name+"' class='"+content[i].class+"' name='"+content[i].name+"'></select></div>").appendTo( ($("div").hasClass("__ui__g_form_control")) ? $(".__ui__g_form_control") : $(".__ui__g_body") );
        for(var b in select) {
          $(".__ui__g_form_control select").append("<option value='"+select[b].value+"'>"+select[b].text+"</option>");
        }
      } else {
        content[i].label = (content[i].label) ? "<label>"+content[i].label+"</label>" : '';
        if(header) {
          $("<div class='form-group'>"+f(content[i])+content[i].label).appendTo( ($("div").hasClass("__ui__g_form_control")) ? $(".__ui__g_form_control") : $(".__ui__g_body") );
        } else {
          $(f(content[i])+content[i].label).appendTo( ($("div").hasClass("__ui__g_form_control")) ? $(".__ui__g_form_control") : $(".__ui__g_body") );
        }
      }
    }
  }

  // set style
  if(style) {
    this.setUIStyle(style, ".__ui__g .__ui__g_body a");
  }

  $(".__ui__g_header .__ui__g_close").click(
    function(){
      $(".__ui__g").remove();
      $("body").css('overflow','auto');
    });

  $("form").on('submit', function(e){
    e.preventDefault();
    //Process
    for(var i in data) {
      !$.isNumeric(data[i]) ? data[i] = $(data[i]).val() : '';
    }
    App.query(header.method, header.action, data, false, done);
  });
}

App.setUIStyle = function(c, section) {
  for(var i in c) {
    $(section).css(i, c[i]);
  }
}

App.update = function(){
    $(".ty-search button#search").click(function(){
      if($(window).width() < 1004){
      //
      if(!$(".ty-search").hasClass("full-width")){
        $(".navbar > *").css('display','none');
        $(".ty-search").addClass('full-width');
        /* Add full search input block */
        var left_arrow = "<span id='search_back' class='fa fa-arrow-left' style='margin: 9px 5px 0 0;cursor:pointer'></span>";
        $(".ty-search").appendTo($(".navbar"));
        $(left_arrow).insertBefore($(".ty-search input"));
      }
      //
        $(".navbar #search_back").click(function(){
          resetCss();
        });
      }
      // end else

    }); // click event
  if($(window).width() >= 1004)
  {
    if($(".ty-search").hasClass("full-width")){
      resetCss();
    }
  }
  //}// if
} // function

function resetCss(){
  $(".navbar #search_back").remove();
  $(".navbar > *").css('display','block');
  $(".ty-search").removeClass("full-width");
  $(".navbar .user-navbar > li:first-child").prepend($(".ty-search"));
}

function notify(title, text, url = '') {
  if (Notification.permission !== "granted") {
    Notification.requestPermission();
  } else {
    var notification = new Notification(title, {
      icon: '../../upload/57x57/rgb_logo.png',
      body: text,
    });
  }

  if(url != ''){
    notification.onclick = function() {
      window.open(url);
    }
  }
}

App.info = function() {
  $("*[data-script=info]").each(function() {
    $(this).css('cursor','pointer');
    $(this).on("click", function(){
      if($(this).children().hasClass("info-box")){
        $(this).children().remove();
      } else {
        $("<div class='info-box'>"+$(this).data('text')+"</div>").appendTo($(this));
      }
    });
  });
}

App.share = function(data) {
  // share
  var share = {};

  var links = {
    // name: [icon, url]
    'facebook': ['fa-facebook', 'https://www.facebook.com/share.php?u='],
    'linkedin': ['fa-linkedin', 'https://www.linkedin.com/sharing/share-offsite/?url=']
    // more...
  };

  // UX create

  var $this = $(data.appendTo);

  $this.click(function() {
      if($(this).hasClass("sb-open")) {
        $(this).children(".sb-dialog").remove();
        $(this).removeClass("sb-open");
      } else {
        $(this).addClass("sb-open");
        $("<div class='sb-dialog'></div>").appendTo( $(this) );
        $.each(data.apps, function(f) {
          $("<a href='"+ links[data.apps[f]][1] + window.location.href + "'><div class='fab "+ links[data.apps[f]][0] +"'></div></a>").appendTo( $(".sb-dialog") );
        });
      }
  });

  //----------

}
