const addAuxButton = document.querySelector(".addAux");
const auxAddButton = document.querySelector(".auxAddButton");

addAuxButton.onclick = () => {
  $(".addAuxActPrompt").addClass("model-open");
};

auxAddButton.onclick = () => {
  const auxName = document.querySelector(".auxName");
  console.log(auxName.value);
  sendReqForAddingAux(auxName.value);
};
function sendReqForAddingAux(auxName) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/addAuxAct.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        var json = JSON.parse(xhr.responseText);
        if (json.success) {
          console.log("JSON", json);
          console.log("Aux Added Successfully!");
        } else {
          console.log("Aux not added!");
        }
        console.log(json);
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("auxName=" + auxName + "&storyId=" + storyId);
}
