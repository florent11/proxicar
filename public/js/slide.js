class Slide 
{
  slideItems1()
  {
    $(".items-panel-1").hide();
    $(".icon-userpanel-1").click(function(){
    $(".items-panel-1").slideToggle();
    });
  }
  
  slideItems2()
  {
    $(".items-panel-2").hide();
    $(".icon-userpanel-2").click(function(){
    $(".items-panel-2").slideToggle();
    });
  }
}
const slide = new Slide
slide.slideItems1();
slide.slideItems2();