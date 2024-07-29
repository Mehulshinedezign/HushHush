

var dbRef = db.ref(`/users`);
dbRef.once("value").then(snap => {
    snap.forEach((message) => {
        console.log(message.val().name);

        $('.chatlist').append(`<li>
        <div class="chat-list-profile">
          <div class="chat-profile-img-box">
               <div class="chat-profile-img">
                 
             </div>
             <p>
                
                  {{ @$chat->customer->name }}
             
            </p>
         </div>
        <span>{{ date('h:i a', strtotime(@$chat->last_msg_datetime)) }}</span>
    </div>
</li>`);

    });
}).catch(error => {
    console.log("error".error)
})

