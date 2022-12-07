function setRemPx(pwidth,prem){
    var oWidth = document.body ? document.body.clientWidth : document.documentElement.clientWidth;
    oWidth = oWidth > pwidth ? pwidth : oWidth;
    var html = document.getElementsByTagName("html")[0];
    html.style.fontSize = oWidth/pwidth*prem + "px";
}
setRemPx(750,100);
window.onresize = function(){
    setRemPx(750,100);
/*640代表设计师给的设计稿的宽度，你的设计稿是多少，就写多少;
  100代表换算比例，这里写100是为了以后好算,
  比如，你测量的一个宽度是100px,就可以写为1rem,以及1px=0.01rem等等
*/
};