function shareStory() {
  var emailOfUser = document.querySelector(".emailOfOthUser");
  if (emailOfUser.value) {
    let xhrShareStory = new XMLHttpRequest();
    xhrShareStory.open("POST", "./php/shareStory.php", true);
    xhrShareStory.onload = () => {
      if (xhrShareStory.readyState === XMLHttpRequest.DONE) {
        if (xhrShareStory.status === 200) {
          let data = xhrShareStory.response;
          var jsonShareStory = JSON.parse(xhrShareStory.responseText);
          if (jsonShareStory.success) {
            $(".shareStory").removeClass("model-open");
            let confirmation = document.querySelector(".sharingConfirmations");
            confirmation.querySelector("p").innerHTML = jsonShareStory.msg;
            $(".sharingConfirmations").addClass("model-open");
          } else {
            console.log("Aux not added!", jsonShareStory);
            $(".shareStory").removeClass("model-open");
            let confirmation = document.querySelector(".sharingConfirmations");
            confirmation.querySelector("p").innerHTML = jsonShareStory.msg;
            $(".sharingConfirmations").addClass("model-open");
          }
          console.log(jsonShareStory);
        }
      }
    };
    xhrShareStory.setRequestHeader(
      "Content-type",
      "application/x-www-form-urlencoded"
    );
    xhrShareStory.send("email=" + emailOfUser.value + "&storyId=" + storyId);
  } else {
    console.log("Please enter char name!");
  }
}
