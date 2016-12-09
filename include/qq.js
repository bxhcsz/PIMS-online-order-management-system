document.write("<div id=\"FloatDIV\" style=\"position: absolute;top: 0px; border-right: activeborder 1px solid; border-top: activeborder 1px solid; border-left: activeborder 1px solid; border-bottom: activeborder 1px solid; z-index:9999\">");
document.write("<table width=\"110\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td><img src=\"pims_mysql/skin/qq/1/q1.jpg\" width=\"110\" height=\"23\" onClick=\"closeme()\"></td></tr><tr><td><img src=\"pims_mysql/skin/qq/1/q2.jpg\" width=\"110\" height=\"47\"></td></tr><tr><td background=\"pims_mysql/skin/qq/1/line.jpg\" style=\"background-repeat:repeat-y\"><ul style=\"list-style:none;margin:0px;padding-left:7px\">");
//start
document.write("<li><img src=\"pims_mysql/skin/public/qq.jpg\" width=\"21\" height=\"19\"><a target=\"_blank\" href=\"http://wpa.qq.com/msgrd?v=3&uin=37470931&site=qq&menu=yes\">在线咨询</a></li>");
//end
document.write("</ul></td></tr><tr><td><img src=\"pims_mysql/skin/qq/1/q3.jpg\" width=\"110\" height=\"46\"></td></tr></table></div>");
  var MarginLeft = 10;
  var MarginTop = 190;
  var Width = 110;
  var Heigth= "";
  function closeme()
  {
	var test = document.getElementById("FloatDIV");  
    test.parentNode.removeChild(FloatDIV);  
	  }
  function Set()
  {
      document.getElementById("FloatDIV").style.width = Width + 'px';
      document.getElementById("FloatDIV").style.height = Heigth + 'px';
  }
  function Move()
  {
        var b_top = window.pageYOffset  
                || document.documentElement.scrollTop  
                || document.body.scrollTop  
                || 0;
        var b_width= document.body.clientWidth;
      document.getElementById("FloatDIV").style.top = b_top + MarginTop + 'px';
      document.getElementById("FloatDIV").style.left = b_width - Width - MarginLeft + 'px';
      setTimeout("Move();",100);
  }
  Set();
  Move();
