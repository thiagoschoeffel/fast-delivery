$(document).ready(function () {
  var timeNext = 59;
  var timePause = false;

  var timer = setInterval(function () {
    if (!timePause) {
      if (timeNext >= 0) {
        $(".j_contador_atualizacao").text(
          moment.utc(timeNext * 1000).format("ss")
        );
        timeNext--;
      } else {
        window.location.reload();
      }
    }
  }, 1000);
});
