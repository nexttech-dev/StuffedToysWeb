const communityList = document.querySelector(".communityList"),
  communityTable = document.querySelector(".communityTable");

function loadCommunity() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/community/community.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        var json = JSON.parse(xhr.responseText);
        // console.log(json);
        details.style.display = "none";
        communityList.style.display = "contents";
        //   content.innerHTML = json.profile;
        communityList.innerHTML = json.community;
        // $(document).ready(function () {
        //   $(".custom-model-main").removeClass("model-open");
        // });
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send();
}
function view_topic(id) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/community/topic.php?id="+id, true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        var json = (function(raw) {
          try {
            return JSON.parse(raw);
          } catch (err) {
            return false;
          }
        })(xhr.responseText);
        details.style.display = "none";
        communityList.style.display = "contents";
        communityList.innerHTML = json.community;
        $('.text-jqte').jqte();
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send();
}
// document.querySelector(".savetopic").addEventListener("click", function(){ alert("Hello World!"); });

function saveTopic(form_id='manage-topic') {
  // console.log('saveTopic');
  var form = document.querySelector('#'+form_id);
  var data = new FormData(form);
  const url = "php/community/manage.php";
  fetch(url, {
    method : "POST",
    body: data,
  }).then(
      response => response.json() // .json(), etc.
  ).then(
      (Res) => {
        if(Res.result==1){
          loadCommunity();
          $(".custom-model-main").removeClass("model-open");
        }else{
          console.log("Something went wrong!");
        }
        console.log('Success:', Res);
      }

      );
  return false;
}
function saveComment(form_id='manage-comment',id) {
  var form = document.querySelector('#'+form_id);
  var data = new FormData(form);
  const url = "php/community/manage.php";
  fetch(url, {
    method : "POST",
    body: data,
  }).then(
      response => response.json() // .json(), etc.
  ).then(
      (Res) => {
        if(Res.result==1){
          view_topic(id);
          $(".custom-model-main").removeClass("model-open");
        }else{
          console.log("Something went wrong!");
        }
        console.log('Success:', Res);
      }

      );
  return false;
}
function delete_topic(id){

  fetch("php/community/manage.php?action=topic_delete&id="+id).then(
      response => {
        return response.json();
        console.log(response);
    if (!response.ok) {
      throw `Server error: [${response.status}] [${response.statusText}] [${response.url}]`;
    }
    var t;
    console.log(t=response.text());
    return t;
  }).then(
      (Res) => {
        if(Res.result==1){
          loadCommunity();
         // $(".custom-model-main").removeClass("model-open");
        }else{
          console.log("Something went wrong!");
        }
        console.log('Success:', Res);
      }

  );
  return false;
}
function delete_comment(id,topic){

  fetch("php/community/manage.php?action=comment_delete&id="+id).then(
      response => {
        return response.json();
        console.log(response);
    if (!response.ok) {
      throw `Server error: [${response.status}] [${response.statusText}] [${response.url}]`;
    }
    var t;
    console.log(t=response.text());
    return t;
  }).then(
      (Res) => {
        if(Res.result==1){
          view_topic(topic);
         // $(".custom-model-main").removeClass("model-open");
        }else{
          console.log("Something went wrong!");
        }
        console.log('Success:', Res);
      }

  );
  return false;
}
function continueStory(data) {
  console.log("Data", data.id);
  window.location = "./writingtool/index.php?storyId=" + data.id + "&chapter=1";
}
function deleteStory(data) {
  console.log("Data", data.id);

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/community/deleteStory.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        var json = JSON.parse(xhr.responseText);
        console.log(json);
        location.reload();
        // details.style.display = "none";
        // communityList.style.display = "contents";
        // //   content.innerHTML = json.profile;
        // communityTable.innerHTML = json.community;
        // $(document).ready(function () {
        //   $(".custom-model-main").removeClass("model-open");
        // });
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("storyId=" + data.id);
}
