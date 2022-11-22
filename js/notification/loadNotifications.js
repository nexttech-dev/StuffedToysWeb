const notifi = document.querySelector(".notifi"),
  notificationCount = notifi.querySelector(".icon span"),
  notifications = notifi.querySelector(".notifi-box");
let xhr = new XMLHttpRequest();
xhr.open("POST", "./php/notifications/loadingNotifications.php", true);
xhr.onload = () => {
  if (xhr.readyState === XMLHttpRequest.DONE) {
    if (xhr.status === 200) {
      let data = xhr.response;
      // var json = JSON.parse(xhr.responseText);
      var json = (function(raw) {
        try {
          return JSON.parse(raw);
        } catch (err) {
          return false;
        }
      })(xhr.responseText);
      if(json){
        notifications.innerHTML = json.data;
        notificationCount.innerHTML = json.count;
      }
      console.log("data", data);
    }
  }
};
xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhr.send();
