const sendBtn = document.querySelector(".Click-here");
inputField = document.querySelector(".input-field");
const chatBox = document.querySelector(".chat-box");

inputField.focus();
inputField.onkeyup = () => {
  if (inputField.value != "") {
    sendBtn.classList.add("active");
  } else {
    sendBtn.classList.remove("active");
  }
};

function scrollToBottom() {
  chatBox.scrollTop = chatBox.scrollHeight;
}
chatBox.onmouseenter = () => {
  chatBox.classList.add("active");
};

chatBox.onmouseleave = () => {
  chatBox.classList.remove("active");
};

function delMsg(params) {
  const msgIdToDel = $(params).attr("id");
  console.log(msgIdToDel);
  console.log(params);
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/delMsgs.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        var json = JSON.parse(xhr.responseText);
        if (json.success) {
          console.log("Message Deleted Succesfully!", json);
          scrollToBottom();
        } else {
          console.log("Message Not Deleted!");
        }
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("msgId=" + msgIdToDel + "&storyId=" + storyId);
}

function updateMsg(msgIdToUpdate, chatText) {
  chatText = chatText.replace(/\r?\n|\r/g, " ");
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/updateMsgs.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        var json = JSON.parse(xhr.responseText);
        console.log("JSON Data", json);
        if (json.result) {
          console.log("Message Updated Succesfully!", json);
          scrollToBottom();
        } else {
          console.log("Message Not Updated!");
        }
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send(
    "msgId=" + msgIdToUpdate + "&storyId=" + storyId + "&chatText=" + chatText
  );
}
function updateChar(event, element) {
  const charName = $(element).attr("id");

  if (event.keyCode == 13) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./php/updateCharName.php", true);
    xhr.onload = () => {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          let data = xhr.response;
          var json = JSON.parse(xhr.responseText);
          if (json.success) {
            console.log("Char Name updated Succesfully!", json);
            scrollToBottom();
          } else {
            console.log("Char Not Updated!");
          }
        }
      }
    };
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(
      "charName=" +
        charName +
        "&storyId=" +
        storyId +
        "&charNewName=" +
        element.value
    );
  }
}
var prevData = "";
var prevMsgsIndex;
var prevMsgsJson;
setInterval(() => {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/loadStories.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        var json = JSON.parse(xhr.responseText);
        if (json.result && !json.error) {
          if (JSON.stringify(prevMsgsJson) == JSON.stringify(json.onlyMsgs)) {
            console.log("Exactly Smae!");
          } else {
            if (prevMsgsJson && prevMsgsJson.length != json.onlyMsgs.length) {
              if (prevMsgsJson.length > json.onlyMsgs.length) {
                const results = prevMsgsIndex.filter(
                  ({ msgId: id1 }) =>
                    !json.msgsWithIndex.some(({ msgId: id2 }) => id2 === id1)
                );
                const elements = document.getElementsByClassName(
                  "msg" + results[0]["msgId"]
                );
                while (elements.length > 0) {
                  elements[0].parentNode.removeChild(elements[0]);
                }
                console.log("Msg has been deleted!");
              } else {
                console.log("Msg has been added!");
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "./php/newMsgs.php", true);
                xhr.onload = () => {
                  if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                      let data = xhr.response;
                      var json = JSON.parse(xhr.responseText);
                      data = json.userID;
                      document
                        .getElementById("chatBox")
                        .insertAdjacentHTML("beforeend", data);

                      if (!chatBox.classList.contains("active")) {
                        scrollToBottom();
                      }
                    } else {
                      console.log("Error");
                    }
                  }
                };
                xhr.setRequestHeader(
                  "Content-type",
                  "application/x-www-form-urlencoded"
                );
                xhr.send("storyId=" + storyId + "&char=2");
              }

              console.log("New Message Added or deleted");
            } else if (prevMsgsJson) {
              for (let index = 0; index < json.onlyMsgs.length; index++) {
                let key = Object.keys(json.onlyMsgs[index])[0];
                if (json.onlyMsgs[index][key] != prevMsgsJson[index][key]) {
                  let msgToModify = document.querySelector(
                    ".msgId" + key + " textarea"
                  );
                  msgToModify.innerHTML = json.onlyMsgs[index][key];
                }
              }
              console.log("Message Modified");
            } else {
              prevData = json.msg;
              console.log(json.onlyMsgs);
              chatBox.innerHTML = json.msg;
            }
            prevMsgsJson = json.onlyMsgs;
            prevMsgsIndex = json.msgsWithIndex;
          }
          if (!chatBox.classList.contains("active")) {
            scrollToBottom();
          }
        } else if (!json.result && json.error) {
          console.log("No messages Found!");
        }
      } else {
        console.log("Error");
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("storyId=" + storyId + "&char=2");
}, 500);
