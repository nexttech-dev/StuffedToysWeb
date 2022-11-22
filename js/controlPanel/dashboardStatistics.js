const totalWords = document.querySelector(".totalWords"),
  totalEarnings = document.querySelector(".totalEarnings"),
  totalStories = document.querySelector(".totalStories"),
  totalCompletedStories = document.querySelector(".totalCompletedStories");

let xhrWordCount = new XMLHttpRequest();
xhrWordCount.open("POST", "./php/controlPanel/dashboardStatistics.php", true);
xhrWordCount.onload = () => {
  if (xhrWordCount.readyState === XMLHttpRequest.DONE) {
    if (xhrWordCount.status === 200) {
      let data = xhrWordCount.response;
      var json = JSON.parse(xhrWordCount.responseText);
      console.log(json);
      if (json.success) {
        totalWords.innerHTML = json.result;
        let totalEarning = 0.5 * parseInt(json.result);
        totalEarnings.innerHTML = "$" + totalEarning;
        totalStories.innerHTML = json.totalStories;
        totalCompletedStories.innerHTML = json.totalCompletedStories;
      } else {
        totalWords.innerHTML = "0";
        totalEarnings.innerHTML = "$0";
        totalStories.innerHTML = "0";
        totalCompletedStories.innerHTML = "0";
      }
    }
  }
};
xhrWordCount.setRequestHeader(
  "Content-type",
  "application/x-www-form-urlencoded"
);
xhrWordCount.send();
