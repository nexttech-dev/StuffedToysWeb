// Sending Options
const actor = document.querySelector(".actor");
const innerThoughts = document.querySelector(".innerThoughts");
const director = document.querySelector(".director");
const auxilary = document.querySelector(".auxButtonPrompt");

// Msg to send
const msg = document.querySelector(".msgToSend");

// Input Field
const inputField = document.querySelector(".input-field");

actor.onclick = () => {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/sendMsg.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        var json = JSON.parse(xhr.responseText);
        if (json.result) {
          console.log("JSON DATA", json);
          console.log("Message Sent Successfully!");
          inputField.value = "";
          scrollToBottom();
          document
            .querySelector(".custom-model-main")
            .classList.remove("model-open");
        } else {
          console.log("JSON DATA", json);
          console.log("Message Not Sent!");
        }
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send(
    "sendAs=Actor&msg=" + msg.value + "&storyId=" + storyId + "&charCode=null"
  );
};
innerThoughts.onclick = () => {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/sendMsg.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        var json = JSON.parse(xhr.responseText);
        if (json.result) {
          console.log("JSON DATA", json);
          console.log("Message Sent Successfully!");
          inputField.value = "";
          scrollToBottom();
          document
            .querySelector(".custom-model-main")
            .classList.remove("model-open");
        } else {
          console.log("JSON DATA", json);
          console.log("Message Not Sent!");
        }
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send(
    "sendAs=InnerThoughts&msg=" +
      msg.value +
      "&storyId=" +
      storyId +
      "&charCode=null"
  );
};

director.onclick = () => {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/sendMsg.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        var json = JSON.parse(xhr.responseText);
        if (json.result) {
          console.log("JSON DATA", json);
          console.log("Message Sent Successfully!");
          inputField.value = "";
          scrollToBottom();
          document
            .querySelector(".custom-model-main")
            .classList.remove("model-open");
        } else {
          console.log("JSON DATA", json);
          console.log("Message Not Sent!");
        }
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send(
    "sendAs=Director&msg=" +
      msg.value +
      "&storyId=" +
      storyId +
      "&charCode=null"
  );
};

function auxMsgSend(self) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/sendMsg.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        var json = JSON.parse(xhr.responseText);
        if (json.result) {
          console.log("JSON DATA", json);
          console.log("Message Sent Successfully!");
          inputField.value = "";
          scrollToBottom();
          document
            .querySelector(".custom-model-main")
            .classList.remove("model-open");
        } else {
          console.log("JSON DATA", json);
          console.log("Message Not Sent!");
        }
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send(
    "sendAs=aux&msg=" +
      msg.value +
      "&storyId=" +
      storyId +
      "&charCode=" +
      self.id
  );
}

function scrollToBottom() {
  chatBox.scrollTop = chatBox.scrollHeight;
}
